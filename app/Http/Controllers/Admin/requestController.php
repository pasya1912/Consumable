<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//import db
use Illuminate\Support\Facades\DB;

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

    function export(Request $request)
    {
        //receive excell ( validate first )
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        $file = $request->file('file');


    }

}
