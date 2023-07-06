<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//import facade DB
use Illuminate\Support\Facades\DB;

//import request item model
use App\Models\ItemRequest;

//request model
use App\Models\Requests as RequestModel;


class requestHistoryController extends Controller
{
    function index(Request $request)
    {
        //get list of request from database where user is the same as logged in user order by tanggal and id_jam desc
        $find= $request->query('search') == null ? '' : $request->query('search');
        $reqList = DB::table('request')
        ->select('request.id','request.tanggal','request.nama','request.status','request.user',DB::raw('concat(jadwal.awal,":",jadwal.akhir) as jam_pengambilan'))
        ->leftJoin('jadwal','request.id_jam','=','jadwal.id')
        ->where('request.user',$request->user()->username)
        ->where(function ($subQuery) use ($find){
            $subQuery = $subQuery->orWhere('request.nama', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('request.status', 'LIKE', "%".$find."%");
        })
        ->orderBy('request.tanggal','desc')
        ->orderBy('request.id_jam','desc')

        ->orderBy('request.id','desc')
        ->paginate(10)->appends(request()->query())->toArray();


        return view('user.requestHistory',compact('reqList'));
    }
    function detail($id,Request $request)
    {
        try{
        $reqDetail = DB::table('request')
        ->select('request.id','request.tanggal','request.nama','request.status','request.user',DB::raw('concat(jadwal.awal,":",jadwal.akhir) as jam_pengambilan'))
        ->leftJoin('jadwal','request.id_jam','=','jadwal.id')
        ->where('request.id',$id)
        ->where('request.user',$request->user()->username)
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
        ->select('request_item.code_item','request_item.admin_note','request_item.jumlah','item_master.name_item','item_master.satuan','item_master.satuan_oca','item_master.convert')
        ->leftJoin('item_master','request_item.code_item','=','item_master.code_item')
        ->where('request_item.id_request',$id)
        ->get();
        $reqDetail->items = $reqItem;

        }
        catch(\Exception $e){
            $reqDetail->items = [];
        }




        return view('user.requestHistoryDetail',compact('reqDetail'));
    }
    function cancel($id,Request $request)
    {
        //delete if request is on wait status, else rollback thenreturnto request history
        try{
            $req = RequestModel::where('id',$id)->where('user',$request->user()->username)->first();
            if(!$req){
                throw new \Exception('Request tidak ditemukan');
            }
            if($req->status != 'wait'){
                throw new \Exception('Request tidak dapat dibatalkan');
            }
            $req->update(['status'=>'canceled']);

            return redirect()->route('user.requestHistory')->with('message','Request berhasil dibatalkan');
        }
        catch(\Exception $e){
            return redirect()->route('user.requestHistory')->with('message',$e->getMessage());
        }

    }

}
