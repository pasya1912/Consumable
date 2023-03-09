<?php

namespace App\Http\Controllers;

use App\Models\TtDc;
use App\Models\TtMa;
use App\Models\TmBom;
use App\Models\TmArea;
use App\Models\TmPart;
use App\Models\TtOutput;
use App\Models\TtStock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function stock_control($line , $code)
    {
        //search code part in tm part number table
        $idPart = TmPart::select('id')->where('part_number',$code);

        //search area in tm area table
        $idArea = TmArea::select('id')->area('name',$line);

        //search bom of the part number based on line in tm bom table
        $bomQty = TmBom::select('id','id_partBom','qty_use')
                    ->where('id_area', $idArea)
                    ->where('id_part', $idPart)
                    ->get();

        // get current stock quantity
        $currStock = TtStock::select('qty')
                        ->where('id_part',$bomQty->id_partBom)
                        ->first();

        //modify quantiy of material in tt stock table
        $updateStock = TtStock::where('id_part',$bomQty->id_partBom)
                        ->update([
                            'qty' => $currStock - $bomQty->qty_use
                        ]);

        // insert id bom inside tt output table
        TtOutput::create([
            'id_bom' => $bomQty->id,
            'date' => date('Y-m-d'),
        ]);

        // get current wip quantity
        $currentDcStock = TtDc::select('qty')->where('part_number',$code)->first();
        $currentMaStock = TtMa::select('qty')->where('part_number',$code)->first();

        //insert part number in wip table (tt dc/tt ma) based on line
        if(strpos($line, 'DC')){
            // wip DC
            TtDc::where('part_number', $code)->updateOrCreate([
                'part_number' => $code,
                'qty' => $currentDcStock + 1
            ]);
            
        }elseif(strpos($line, 'MA')){
            // modify wip DC stock
            TtDc::where('part_number', $code)->update([
                'part_number' => $code,
                'qty' => $currentDcStock - 1
            ]);

            // wip MA
            TtMa::where('part_number', $code)->updateOrCreate([
                'part_number' => $code,
                'qty' => $currentMaStock + 1
            ]);
        }


        return response()->json([
            'message' => 'success'
        ],200);
    }
}
