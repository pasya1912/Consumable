<?php

namespace App\Http\Controllers;

use App\Jobs\exportRequest;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class exportController extends Controller
{
    public function generate_detail($id, Request $request)
    {
        $exportDetail = DB::table('export')->where('id_request', $id)->first();
        $reqDetail = DB::table('request')->where('id',$id)->first();
        //load view print with domPdf with size A4
        if (!$exportDetail || ($reqDetail && $reqDetail->status != 'camceled')) {
            exportRequest::dispatch($id);

            return redirect()->back()->with('message', 'Request sedang diexport, lakukan lagi setelah beberapa menit');
        }
        else{
            
            return redirect()->back()->with('message', 'Sudah ada export, silahkan click export');

        }
        
    }
}
