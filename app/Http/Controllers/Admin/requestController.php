<?php

namespace App\Http\Controllers\Admin;


use App\Exports\requestListExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\exportRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//import db
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class requestController extends Controller
{
    function history(Request $request)
    {
        try {
            $find = $request->query('search') == null ? '' : $request->query('search');
            $reqList = DB::table('request')
                ->select('request.id', 'request.tanggal', 'request.nama', 'request.status', 'request.user', DB::raw('concat(jadwal.awal,":",jadwal.akhir) as jam_pengambilan'))
                ->leftJoin('jadwal', 'request.id_jam', '=', 'jadwal.id')
                ->where(function ($subQuery) use ($find) {
                    $subQuery = $subQuery->orWhere('request.nama', 'LIKE', "%" . $find . "%");
                    $subQuery = $subQuery->orWhere('request.user', 'LIKE', "%" . $find . "%");
                    $subQuery = $subQuery->orWhere('request.status', 'LIKE', "%" . $find . "%");
                })
                ->orderBy('request.tanggal', 'desc')
                ->orderBy('request.id_jam', 'desc')
                ->orderBy('request.id', 'desc')
                ->paginate(10)->appends(request()->query())->toArray();
        } catch (\Exception $e) {
            $reqList = [];
        }


        return view('admin.requestHistory', compact('reqList'));
    }
    function detail($id, Request $request)
    {
        try {
            $reqDetail = DB::table('request')
                ->select('request.id', 'request.tanggal', 'request.nama', 'request.status', 'request.user', DB::raw('concat(jadwal.awal,":",jadwal.akhir) as jam_pengambilan'))
                ->leftJoin('jadwal', 'request.id_jam', '=', 'jadwal.id')
                ->where('request.id', $id)
                ->first();
            if (!$reqDetail) {

                throw new \Exception('Request tidak ditemukan');
            }
        } catch (\Exception $e) {

            return redirect()->route('user.requestHistory')->with('message', 'Request tidak ditemukan');
        }
        try {
            $reqItem = DB::table('request_item')
                ->select('request_item.code_item', 'request_item.jumlah', 'request_item.id', 'request_item.admin_note', 'item_master.name_item', 'item_master.satuan', 'item_master.satuan_oca', 'item_master.convert', 'request_item.admin_note')
                ->leftJoin('item_master', 'request_item.code_item', '=', 'item_master.code_item')
                ->where('request_item.id_request', $id)
                ->get();
            $reqDetail->items = $reqItem;
        } catch (\Exception $e) {
            dd($e);
            $reqDetail->items = [];
        }

        return view('admin.requestDetail', compact('reqDetail'));
    }
    function updateStatus($id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,canceled,revised'
        ]);
        //update request table from post data
        $status = $request->input('status');
        //check if status wait then change to status from input
        $req = DB::table('request')->where('id', $id)->first();
        if (!$req) {
            return redirect()->route('admin.requestHistory')->with('message', 'Request tidak ditemukan');
        }


        if (DB::table('request')->where('id', $id)->update(['status' => $status])) {
            return redirect()->route('admin.requestDetail', ['id' => $id])->with('message', 'Request berhasil diupdate');
        } else {
            return redirect()->route('admin.requestDetail', ['id' => $id])->with('message', 'Request gagal diupdate');
        }
    }
    public function update($id, Request $request)
    {
        //CHECK IF CONTENT TYPE JSON
        if ($request->header('Content-Type') == 'application/json') {
            $keys = array_keys($request->data);
            $arr = [];
            foreach ($keys as $key) {
                $arr = array_merge($arr, [$key => $request->data[$key]]);
            }


            $update = DB::table('request_item')->where('id', $id)->update($arr);
            if ($update) {
                return response()->json(['status' => true, 'message' => 'Berhasil update ']);
            } else {
                return response()->json(['status' => false, 'message' => 'Gagal update ']);
            }
        } else {
            if (isset($request->status)) {
                $request->validate([
                    'status' => 'required|in:approved,rejected,canceled,revised'
                ]);
                //update request table from post data
                $status = $request->input('status');

                //check if status wait then change to status from input
                $req = DB::table('request')->where('id', $id)->first();
                if (!$req) {
                    return redirect()->route('admin.requestHistory')->with('message', 'Request tidak ditemukan');
                }

                if (DB::table('request')->where('id', $id)->update(['status' => $status])) {



                    if ($request->status == 'approved') {
                        exportRequest::dispatch($id);
                    }

                    return redirect()->route('admin.requestDetail', ['id' => $id])->with('message', 'Request berhasil diupdate');
                } else {
                    return redirect()->route('admin.requestDetail', ['id' => $id])->with('message', 'Request gagal diupdate');
                }
            }
        }
    }

    function export($id, Request $request)
    {



        $reqDetail = DB::table('export')->where('id_request', $id)->first();
        //load view print with domPdf with size A4
        if (!$reqDetail) {
            return redirect()->route('admin.requestHistory')->with('message', 'Tidak dapat mengexport request');
        }
        $reqDetail->data = json_decode($reqDetail->data);

        $pdf = PDF::loadView('print', compact('reqDetail'))->setPaper('a4', 'potrait');

        //download pdf
        return $pdf->stream('request-' . $id . '.pdf');
        ;
    }
    public function exportList(Request $request)
    {
        //if not null it must date

        if($request->from != null || $request->from != ''){
            $request->validate([
                'from' => 'required|date'
            ]);
        }
        if($request->to != null || $request->to != ''){
            $request->validate([
                'to' => 'required|date'
            ]);
        }

        return Excel::download(new requestListExport($request->from, $request->to), 'request.xlsx');
    }

}
