<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class exportRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $reqDetail = DB::table('request')
            ->select('users.nama as department', 'request.status','request.user as username', 'request.tanggal', 'request.id', 'request.id_jam as shift', DB::raw('concat(jadwal.awal,":",jadwal.akhir) as jam_pengambilan'))
            ->leftJoin('users', 'request.user', '=', 'users.username')
            ->leftJoin('jadwal', 'request.id_jam', '=', 'jadwal.id')
            ->where('request.id', $this->id)
            ->first();


        //get request_item data

        $reqItem = DB::table('request_item')
            ->select('request_item.code_item', 'request_item.jumlah', 'request_item.id', 'request_item.admin_note', 'item_master.name_item', 'item_master.satuan_oca', 'item_master.area', 'item_master.lemari', 'item_master.no2', 'budget.quota')
            ->leftJoin('item_master', 'request_item.code_item', '=', 'item_master.code_item')
            ->leftJoin('budget', 'request_item.code_item', '=', 'budget.code_item')
            ->where('budget.user', $reqDetail->username)
            ->where('id_request', $this->id)
            ->orderBy('request_item.code_item', 'ASC')->get();

            $datetanggal = strtotime($reqDetail->tanggal);
            $month = date('m',$datetanggal);
            $year = date('Y',$datetanggal);
        $code_items = [];
            foreach($reqItem as $req){
            $code_items[] = $req->code_item;
                
            }

        $used = DB::table('request_item')->selectRaw('request_item.code_item,sum(request_item.jumlah) as qty')
            ->join('request', 'request_item.id_request', '=', 'request.id')
            ->where('request.user', $reqDetail->username)
            ->whereIn('request_item.code_item',$code_items)
            ->where('request.id','<=',$this->id)
            ->whereMonth('request.tanggal',$month)
            ->whereYear('request.tanggal',$year)
            ->whereNot('request.status','canceled')
            //where tanggal bulan ini
            ->groupBy('request_item.code_item')
            ->orderBy('request_item.code_item', 'ASC')
            ->get()->toArray();

        foreach ($reqItem as $key => $item) {

            $reqItem[$key]->remaining_quota = $item->quota - $used[$key]->qty;
        }

        //if reqItem notfound then return []
        if (!$reqItem) {
            $reqItem = [];
        }
        //append

        $reqDetail->items = $reqItem;
        $arrsearch = [
            'id_request' => $this->id
        ];
        $arr = ['user' => $reqDetail->department, 'tanggal' => $reqDetail->tanggal, 'shift' => $reqDetail->shift, 'jam_pengambilan' => $reqDetail->jam_pengambilan, 'data' => json_encode($reqDetail->items->toArray())];
        try{
        DB::table('export')->updateOrInsert($arrsearch,$arr);
            var_dump('Success');
        }

        catch(\Illuminate\Database\QueryException $ex){
            var_dump($ex->getMessage());
        }
    }
}
