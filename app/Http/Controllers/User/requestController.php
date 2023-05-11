<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

//use auth
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Cache;

class requestController extends Controller
{
    function index(Request $request)
    {

        //forget cart_items session and cart_jumlah session

        $cart_items = $request->session()->get('cart_items');
        $cart_jumlah = $request->session()->get('jumlah_items');
        //if empty return to dashboard with error message
        if(empty($cart_items) || empty($cart_jumlah))
        {
            return redirect()->route('user.dashboard')->with('message','Cart is empty');
        }
        //get item details from database
        $items = DB::table('item_master')
            ->select('item_master.*','budget.quota')
            ->join('budget','item_master.code_item','=','budget.code_item')
            ->whereIn('item_master.code_item',$cart_items)
            ->where('budget.user',$request->user()->username)
        ->get()->toArray();
        //calculate remaining quota
        $req = DB::table('request_item')
            ->selectRaw('request_item.code_item,sum(request_item.jumlah) as qty')
            ->join('request','request_item.id_request','=','request.id')
            ->where('request.user',$request->user()->username)
            //where tanggal bulan ini
            ->whereMonth('request.tanggal',date('m'))
            ->whereYear('request.tanggal',date('Y'))
            ->whereNotIn('request.status',['rejected','canceled'])
            ->groupBy('request_item.code_item')
            ->get()->toArray();
        // append items to request only remaining_quota which is quota - qty

        foreach ($items as $key => $value) {

            $items[$key]->remaining_quota = $value->quota;
            foreach ($req as $key2 => $value2) {
                if($value->code_item == $value2->code_item){
                    $items[$key]->remaining_quota = $value->quota - $value2->qty;
                }
            }
        }
        //get data from table jadwal

        //append jumlah_items to items

        //merge  items and thecart
        $arr= array();
        $thecart = array();
        foreach ($cart_items as $key => $value) {
            $thecart[$key]['code_item'] = $cart_items[$key];
            $thecart[$key]['jumlah'] = $cart_jumlah[$key];
        }
        $arr = ["items" => $items,"cart" => $thecart];

        //from table jadwal get all
        $jadwal = DB::table('jadwal')->get()->toArray();
        $arr = ["jadwal"=>$jadwal,...$arr];


        return view('user.request',compact('arr'));
    }
    function getItem($id)
    {
        $items = Item::where('no',$id)->get();
        return $items;
    }
    function addItem(Request $request)
    {
        //forget session cart_items


        //get request data code from post request
        //validate
        $request->validate([
            'code' => 'required',
            'jumlah' => 'required|numeric'
        ]);
        $id = $request->code;
        $jumlah = $request->jumlah;
        $jumlah = (float)$jumlah;
        //check if item session exist
        if($request->session()->has('cart_items'))
        {

            //check if value exist in    session
            if(!in_array($id,$request->session()->get('cart_items')))
            {
                //push value to session
                $request->session()->push('cart_items', $id);
                if($request->session()->has('jumlah_items'))
                {
                    $request->session()->push('jumlah_items', $jumlah);
                    return response('Item added', 200);
                }
                else{
                    $request->session()->put('jumlah_items',[$jumlah]);
                    return response('Item added', 200);
                }


            }
            else{
                //return error status http
                return response('Item already exist', 400);
            }
        }
        else
        {
            $request->session()->put('cart_items',[$id]);
            $request->session()->put('jumlah_items',[$jumlah]);
            return response('Item added', 200);
        }


    }

    function ubahCart($index,Request $request)
    {
        //forget session cart_items
        //if action post exist
        if($request->action)
        {
            //get action and index from post request
            $action = $request->action;
            $index = $request->index;
            //get cart_items session
            $cart_items = $request->session()->get('cart_items');
            $cart_jumlah = $request->session()->get('jumlah_items');
            //if action is delete
            if($action == 'delete')
            {
                //delete item from cart_items session
                unset($cart_items[$index]);
                unset($cart_jumlah[$index]);
                //reindex cart_items session
                $cart_items = array_values($cart_items);
                $cart_jumlah = array_values($cart_jumlah);
                //put cart_items session
                $request->session()->put('cart_items',$cart_items);
                $request->session()->put('jumlah_items',$cart_jumlah);

                return response('Item deleted', 200);
            }
            //if action is update
            else if($action == 'update')
            {
                //get jumlah from post request
                $jumlah = $request->jumlah;
                $jumlah = (float)$jumlah;

                //update jumlah in cart_jumlah session
                $cart_jumlah[$index] = $jumlah;
                //put cart_jumlah session
                $request->session()->put('jumlah_items',$cart_jumlah);
                return response('Item updated', 200);
            }
            else
            {
                //return error status http
                return response('Bad request', 400);
            }
        }
        else
        {
            //return error status http
            return response('Bad request', 400);
        }
    }
    function checkQuota($code,$jml)
    {
        //get cart_items session

        //get item details from database
        $items = DB::table('item_master')
            ->select('item_master.*','budget.quota')
            ->join('budget','item_master.code_item','=','budget.code_item')
            ->where('item_master.code_item',$code)
            ->where('budget.user',Auth::user()->username)
        ->first();
        //calculate remaining quota
        $req = DB::table('request_item')
            ->selectRaw('request_item.code_item,sum(request_item.jumlah) as qty')
            ->join('request','request_item.id_request','=','request.id')
            ->where('request.user',Auth::user()->username)
            ->whereMonth('request.tanggal',date('m'))
            ->whereYear('request.tanggal',date('Y'))
            ->where('request_item.code_item',$code)
            ->whereNotIn('request.status',['rejected','canceled'])
            ->groupBy('request_item.code_item')
            ->first();

            $res = $req;
            //if null set qty to 0
            if($res == null)
            {
                $res = new \stdClass();
                $res->code_item = $code;
                $res->qty = 0;
            }
            return $res;

        // append items to request only remaining_quota which is quota - qty
        $items->remaining_quota = $items->quota - $req->qty;
        //check if remaining quota is less than jumlah
        if($items->remaining_quota < $jml)
        {
            //return error status http
            return false;
        }
        else
        {
            //return success status http
            return true;
        }

    }

    function store(Request $request)
    {
        $jadwal = $request->jadwal;
        $cart_items = $request->session()->get('cart_items');
        $cart_jumlah = $request->session()->get('jumlah_items');
        $nama_pj = $request->nama_pj;
        foreach($cart_items as $key => $value)
        {
            if(!$this->checkQuota($value,$cart_jumlah[$key]))
            {
                //return to cart page with message flash
                return redirect()->route('user.request')->with('message','Quota tidak mencukupi');
            }
        }

        //insert into request and request_item respectively
        DB::beginTransaction();
        try{
        $id = DB::table('request')->insertGetId([
            'user' => $request->user()->username,
            'nama' => $nama_pj,
            'status' => 'wait',
            'id_jam' => $jadwal,
            'user' => $request->user()->username,
            'tanggal' => date('Y-m-d'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')


        ]);

        DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            return redirect()->route('user.request')->with('message','Gagal menyimpan');
        }
        DB::beginTransaction();
        foreach($cart_items as $key => $value)
        {

            try{
                DB::table('request_item')->insert([
                    'id_request' => $id,
                    'code_item' => $value,
                    'jumlah' => $cart_jumlah[$key]
                ]);
            }catch(\Exception $e){
                DB::rollback();
                return redirect()->route('user.request')->with('message','Gagal menyimpan');
            }

        }

        DB::commit();
        //redirect to index and flash success message
        $request->session()->forget('cart_items');
        $request->session()->forget('jumlah_items');



        return redirect()->route('user.dashboard')->with('message','Request berhasil dibuat');


    }
}
