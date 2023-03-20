<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;

//storage
use Illuminate\Support\Facades\Storage;

class dashboardController extends Controller
{
    function index(Request $request)
    {
        //get current username
        $username = $request->user()->username;
        $find = $request->query('search') == null ? '' : $request->query('search');
        //paginate 2 include query
        $items = Item::select('*')
        ->where(function ($subQuery) use ($find){
            $subQuery = $subQuery->orWhere('code_item', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('name_item', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('area', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('lemari', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('no2', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('satuan', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('satuan_oca', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('convert', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('note', 'LIKE', "%".$find."%");

        })
        ->orderBy('no','DESC')
        ->paginate(10)
        ->appends(request()->query())
        ->toArray();
        return view('admin.dashboard',compact('items'));
    }

    function uploadImage(Request $request)
    {



    }


}
