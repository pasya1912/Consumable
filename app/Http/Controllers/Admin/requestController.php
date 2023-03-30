<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//import db
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class requestController extends Controller
{
    function history(Request $request)
    {
        try{
        $find = $request->query('search') == null ? '' : $request->query('search');
        $reqList = DB::table('request')
        ->select('request.id','request.tanggal','request.nama','request.status','request.user',DB::raw('concat(jadwal.awal,":",jadwal.akhir) as jam_pengambilan'))
        ->leftJoin('jadwal','request.id_jam','=','jadwal.id')
        ->where(function ($subQuery) use ($find){
            $subQuery = $subQuery->orWhere('request.nama', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('request.user', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('request.status', 'LIKE', "%".$find."%");
        })
        ->orderBy('request.tanggal','desc')
        ->orderBy('request.id_jam','desc')
        ->orderBy('request.id','desc')
        ->paginate(10)->appends(request()->query())->toArray();
        }
        catch(\Exception $e){
            $reqList = [];
        }


        return view('admin.requestHistory',compact('reqList'));

    }
    function detail($id,Request $request)
    {
        try{
            $reqDetail = DB::table('request')
            ->select('request.id','request.tanggal','request.nama','request.status','request.user',DB::raw('concat(jadwal.awal,":",jadwal.akhir) as jam_pengambilan'))
            ->leftJoin('jadwal','request.id_jam','=','jadwal.id')
            ->where('request.id',$id)
            ->first();
                if(!$reqDetail){

                    throw new \Exception('Request tidak ditemukan');
                }
            }
            catch(\Exception $e){

                return redirect()->route('user.requestHistory')->with('message','Request tidak ditemukan');
            }
            try{
            $reqItem = DB::table('request_item')
            ->select('request_item.code_item','request_item.jumlah','item_master.name_item','item_master.satuan','item_master.satuan_oca','item_master.convert')
            ->leftJoin('item_master','request_item.code_item','=','item_master.code_item')
            ->where('request_item.id_request',$id)
            ->get();
            $reqDetail->items = $reqItem;

            }
            catch(\Exception $e){
                $reqDetail->items = [];
            }

            return view('admin.requestDetail',compact('reqDetail'));
    }
    function update($id,Request $request)
    {
        $request->validate([
            'status'=>'required|in:approved,rejected,canceled'
        ]);
        //update request table from post data
        $status = $request->input('status');
        //check if status wait then change to status from input
        $req = DB::table('request')->where('id',$id)->first();


            if(DB::table('request')->where('id',$id)->update(['status'=>$status]))
            {
                return redirect()->route('admin.requestDetail',['id'=>$id])->with('message','Request berhasil diupdate');
            }
            else{
                return redirect()->route('admin.requestDetail',['id'=>$id])->with('message','Request gagal diupdate');
            }

    }

    function export($id,Request $request)
    {

        //get request data
        $reqDetail = DB::table('request')
        ->select('users.name as department','request.user as username','request.tanggal','request.id','request.id_jam as shift',DB::raw('concat(jadwal.awal,":",jadwal.akhir) as jam_pengambilan'))
        ->leftJoin('users','request.user','=','users.username')
        ->leftJoin('jadwal','request.id_jam','=','jadwal.id')
        ->where('request.id',$id)
        ->first();

        //get request_item data

        $reqItem = DB::table('request_item')
        ->select('request_item.code_item','request_item.jumlah','request_item.id','request_item.admin_note','item_master.name_item','item_master.satuan_oca','item_master.area','item_master.lemari','item_master.no2','budget.quota')
        ->leftJoin('item_master','request_item.code_item','=','item_master.code_item')
        ->leftJoin('budget','request_item.code_item','=','budget.code_item')
        ->where('budget.user',$reqDetail->username)
        ->where('id_request',$id)
        ->orderBy('request_item.code_item','ASC')->get();

        $used = $this->getReqItem($reqDetail->username,$id);
        foreach($reqItem as $key => $item){

            $reqItem[$key]->remaining_quota = $item->quota - $used[$key]->qty;
        }

        //if reqItem notfound then return []
        if(!$reqItem){
            $reqItem = [];
        }
        //append
        $reqDetail->items = $reqItem;
        //load view print with domPdf with size A4
        $pdf = PDF::loadView('print',compact('reqDetail'))->setPaper('a4', 'potrait');

        //download pdf
        return $pdf->stream('request-'.$id.'.pdf');

;
        }
        function getReqItem($user,$reqid)
        {

            $req = DB::table('request_item')->selectRaw('request_item.code_item,sum(request_item.jumlah) as qty')
            ->join('request','request_item.id_request','=','request.id')
            ->where('request.user',$user)
            ->where('request_item.id_request',$reqid)
            //where tanggal bulan ini
            ->whereMonth('request.tanggal',date('m'))
            ->groupBy('request_item.code_item')
            ->orderBy('request_item.code_item','ASC')
            ->get()->toArray();

            return $req;


        }


}
