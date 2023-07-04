<?php

namespace App\Http\Controllers\Admin;


use App\Exports\requestDetailExport;
use App\Exports\requestListExport;
use App\Models\Export;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\exportRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Requests;
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
            $reqDetail->items = [];
        }

        return view('admin.requestDetail', compact('reqDetail'));
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
            $request->validate([
                'id' => 'required|numeric',
            ]);
            $reqi = DB::table('request')->select('request.status', 'request.id')->join('request_item', 'request.id', 'request_item.id_request')->where('request_item.id', $request->id)->first();
            if (isset($arr['jumlah']) && $reqi->status == 'canceled' && ($reqi->status != 'wait' || $reqi->status != "revised")) {

                return response()->json(['status' => false, 'message' => 'Gagal update status ']);
            }


            $update = DB::table('request_item')->where('id', $request->id)->update($arr);
            if ($update) {
                if (isset($arr['jumlah'])) {
                    if (!DB::table('request')->where('id', $reqi->id)->update(['status' => 'revised'])) {
                        return response()->json(['status' => false, 'message' => 'Udah revisi ']);
                    }
                }
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
                if ($req->status != 'wait') {
                    return redirect()->route('admin.requestDetail', ['id' => $req->id])->with('message', 'Request sudah tidak dapat diupdate');
                }

                if (DB::table('request')->where('id', $id)->update(['status' => $status])) {


                    return redirect()->route('admin.requestDetail', ['id' => $id])->with('message', 'Request berhasil diupdate');
                } else {
                    return redirect()->route('admin.requestDetail', ['id' => $id])->with('message', 'Request gagal diupdate');
                }
            }
        }
    }
    function export($id, Request $request)
    {
        $exportDetail = Export::where('id_request', $id)->first();
        if (!$exportDetail) {
            return redirect()->back()->with('message', 'Data tidak ada silahkan generate');
        }
        $exportDetail->nama = $exportDetail->request->nama;
        $exportDetail->status = $exportDetail->request->status;
        $exportDetail->data = json_decode($exportDetail->data);

        $req = [];
        $req = array_merge($req, $exportDetail->toArray());

        if ($exportDetail->status == "canceled") {
            return redirect()->back()->with('message', 'Request yang dibatalkan tidak dapat diexport');
        }

        return Excel::download(new requestDetailExport($req), 'filename.xlsx');


    }
    function print($id, Request $request)
    {


        $exportDetail = Export::where('id_request', $id)->first();
        if (!$exportDetail) {
            return redirect()->back()->with('message', 'Data tidak ada silahkan generate');
        }
        $exportDetail->nama = $exportDetail->request->nama;
        $exportDetail->status = $exportDetail->request->status;
        $exportDetail->data = json_decode($exportDetail->data);

        $req = [];
        $req = array_merge($req, $exportDetail->toArray());
        $reqDetail = DB::table('request')->select('*')->where('id', $id)->first();

        if ($reqDetail->status == "canceled") {
            return redirect()->back()->with('message', 'Request yang dibatalkan tidak dapat diexport');
        }

        //load view print with domPdf with size A4

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('app/template-oca.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();
        //set I9 value to $reqDetail->nama
        $sheet->setCellValue('I9', $exportDetail->user);
        //set I11 to $exportDetail->nama
        $sheet->setCellValue('I11', $exportDetail->nama);
        //set AA9 to $exportDetail->tanggal format d-m-Y
        $sheet->setCellValue('AA9', date('d-m-Y', strtotime($exportDetail->tanggal)));
        //set AE11 to $exportDetail->jam_pengambilan
        $sheet->setCellValue('AE11', $exportDetail->jam_pengambilan);
        //set AI9 to export detail shift
        $sheet->setCellValue('AI9', $exportDetail->shift);
        //if folder export not exist mkdir
        $start = 16;
        foreach($exportDetail->data as $key =>$data){
            $sheet->setCellValue('C'.$start+$key, $data->name_item." ( $data->code_item ) ");
            $sheet->setCellValue('R'.$start+$key, $data->jumlah);
            $sheet->setCellValue('U'.$start+$key, $data->satuan_oca);
            $sheet->setCellValue('Y'.$start+$key, ($data->remaining_quota+$data->jumlah));
            //AB16 = area
            $sheet->setCellValue('AB'.$start+$key, $data->area);
            //AF16 = lemari
            $sheet->setCellValue('AF'.$start+$key, $data->lemari);
            //AH = no2
            $sheet->setCellValue('AH'.$start+$key, $data->no2);
            //AK = admin_note
            $sheet->setCellValue('AK'.$start+$key, $data->admin_note);
            $sheet->setCellValue('AE50','Developed by IS (ITD)');
            $sheet->setCellValue('C50','');
            $sheet->setCellValue('A50','');
        }


        if (!file_exists(storage_path('app/export'))) {
            mkdir(storage_path('app/export'));
        }
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save(storage_path('app/export/' . $id . '.xlsx'));

        return response()->file(storage_path('app/export/' . $id . '.xlsx'));


    }
    public function exportList(Request $request)
    {
        //if not null it must date

        if ($request->from != null || $request->from != '') {
            $request->validate([
                'from' => 'required|date'
            ]);
        }
        if ($request->to != null || $request->to != '') {
            $request->validate([
                'to' => 'required|date'
            ]);
        }

        return Excel::download(new requestListExport($request->from, $request->to), 'request.xlsx');
    }
}
