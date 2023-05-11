<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Budget;
use App\Models\ItemRequest;
//use db facade
use Illuminate\Support\Facades\DB;
//import cache
use Illuminate\Support\Facades\Cache;


class dashboardController extends Controller
{
    function index(Request $request)
    {

        try{
        $find = $request->query('search') == null ? '' : $request->query('search');
        $items = DB::table('item_master')->select('item_master.code_item','item_master.name_item','item_master.note','item_master.satuan','item_master.satuan_oca','item_master.convert','budget.quota')
        ->leftJoin('budget','item_master.code_item','=','budget.code_item')
        ->where(function ($subQuery) use ($find){
            $subQuery = $subQuery->orWhere('item_master.code_item', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('item_master.name_item', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('item_master.note', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('item_master.satuan', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('item_master.satuan_oca', 'LIKE', "%".$find."%");
            $subQuery = $subQuery->orWhere('budget.quota', 'LIKE', "%".$find."%");
        })
        ->where('budget.user',$request->user()->username)
        ->paginate(12)->appends(request()->query())->toArray();



        $req = DB::table('request_item')->selectRaw('request_item.code_item,sum(request_item.jumlah) as qty')
        ->join('request','request_item.id_request','=','request.id')
        ->where('request.user',$request->user()->username)
        //where tanggal bulan ini
        ->whereYear('request.tanggal',date('Y'))
        ->whereMonth('request.tanggal',date('m'))
            ->whereNotIn('request.status',['rejected','canceled'])
        ->groupBy('request_item.code_item')
        ->get()->toArray();
        // append items to request only remaining_quota which is quota - qty
        foreach ($items['data'] as $key => $value) {

            $items['data'][$key]->remaining_quota = $value->quota;
            foreach ($req as $key2 => $value2) {
                if($value->code_item == $value2->code_item){
                    $items['data'][$key]->remaining_quota = $value->quota - $value2->qty;
                }
            }
        }
    }catch(\Exception $e){
        $items = [];
    }

        return view('user.dashboard',compact('items'));
    }

}
