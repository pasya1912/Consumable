<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//include db
use Illuminate\Support\Facades\DB;
use App\Models\Budget;

class budgetController extends Controller
{
    function index(Request $request)
    {

        //ability to sort
        $find = $request->query('search') == null ? '' : $request->query('search');
        $budgets = $this->getBudget(2,$find)->appends(request()->query())->toArray();
        $listUser = [];
        $listCode = [];
        foreach ($budgets['data'] as $key => $value) {
            $budgets['data'][$key]['remaining_quota'] = $value['quota'];
        }
        //get total request item

        //append to budgets array
        foreach ($budgets['data'] as $key => $value) {
            $totem = $this->getTotalRequestItem($value['user'],$value['code_item']);
            $budgets['data'][$key]['remaining_quota'] = $value['quota'];
            foreach ($totem as $key2 => $value2) {
                if($value['code_item'] == $value2->code_item){
                    $budgets['data'][$key]['remaining_quota'] = $value['quota'] - $value2->qty;
                }
            }
        }
        //sort by remaining quota most to least
        usort($budgets['data'], function($a, $b) {
            return $b['remaining_quota'] <=> $a['remaining_quota'];
        });
        //get remaining quota by sum request_item.jumlah where request_item.code_item = budget.code_item for
        return view('admin.budget',compact('budgets'));
    }
    function getBudget($paginate,$find )
    {
        $columns = ['budget.no','item_master.name_item','budget.code_item','budget.category','budget.user','budget.quota'];
        //find like and sort paginate
        $budgets = Budget::where(function ($subQuery) use ($columns, $find){
            foreach ($columns as $column) {
              $subQuery = $subQuery->orWhere($column, 'LIKE', "%".$find."%");
            }})
            ->leftJoin('item_master','budget.code_item','=','item_master.code_item')
            ->select('budget.*','item_master.name_item')
            ->orderBy('budget.quota','desc')
            ->paginate(10);


            //foreach budgets data then make an array of user and code_item

            //get total request item


        //get the sql quer
        return $budgets;
    }
    function getTotalRequestItem($user,$code)
    {

                //count request_item.jumlah where request_item.code_item = budget.code_item and request_item.user = budget.user
        $req = DB::table('request_item')->selectRaw('request_item.code_item,sum(request_item.jumlah) as qty')
        ->join('request','request_item.id_request','=','request.id')
        ->where('request.user',$user)
        ->where('request_item.code_item',$code)
        //where tanggal bulan ini
        ->whereYear('request.tanggal',date('Y'))
        ->whereMonth('request.tanggal',date('m'))
            ->whereNotIn('request.status',['rejected','canceled'])
        ->groupBy('request_item.code_item')
        ->get()->toArray();
        return $req;


    }

}
