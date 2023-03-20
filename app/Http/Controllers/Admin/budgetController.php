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
        $sort = $request->query('sort_by') == null ? 'no' : $request->query('sort_by');
        $find = $request->query('search') == null ? '' : $request->query('search');
        $budgets = $this->getBudget(2, $sort,$find)->appends(request()->query())->toArray();
        $listUser = [];
        $listCode = [];
        foreach ($budgets['data'] as $key => $value) {
            $budgets['data'][$key]['remaining_quota'] = $value['quota'];
            $listUser[] = $value['user'];
            $listCode[] = $value['code_item'];
        }
        //get total request item
        $totem=$this->getTotalRequestItem($listUser,$listCode);
        //append to budgets array
        foreach ($budgets['data'] as $key => $value) {
            foreach ($totem as $key2 => $value2) {
                if($value['code_item'] == $value2->code_item){
                    $budgets['data'][$key]['remaining_quota'] = $value['quota'] - $value2->qty;
                }
            }
        }
        //get remaining quota by sum request_item.jumlah where request_item.code_item = budget.code_item for
        return view('admin.budget',compact('budgets'));
    }
    function getBudget($paginate,$sort,$find )
    {
        $columns = ['budget.no','item_master.name_item','budget.code_item','budget.category','budget.user','budget.quota'];
        //find like and sort paginate
        $budgets = Budget::where(function ($subQuery) use ($columns, $find){
            foreach ($columns as $column) {
              $subQuery = $subQuery->orWhere($column, 'LIKE', "%".$find."%");
            }})
            ->leftJoin('item_master','budget.code_item','=','item_master.code_item')
            ->select('budget.*','item_master.name_item')

            ->orderBy($sort,'asc')
            ->paginate(10);


            //foreach budgets data then make an array of user and code_item

            //get total request item


        //get the sql quer
        return $budgets;
    }
    function getTotalRequestItem($listUser,$listCode)
    {

                //count request_item.jumlah where request_item.code_item = budget.code_item and request_item.user = budget.user
        $req = DB::table('request_item')->selectRaw('request_item.code_item,sum(request_item.jumlah) as qty')
        ->join('request','request_item.id_request','=','request.id')
        ->whereIn('request.user',$listUser)
        ->where('request_item.code_item',$listCode)
        //where tanggal bulan ini
        ->whereMonth('request.tanggal',date('m'))
            ->whereNotIn('request.status',['rejected','canceled'])
        ->groupBy('request_item.code_item')
        ->get()->toArray();
        return $req;


    }

}
