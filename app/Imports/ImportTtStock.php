<?php

namespace App\Imports;

use App\Models\TtStock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Auth;

class ImportTtStock implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   
        $user = Auth::user();
        return new TtStock([
            'part_no' => $row[0],
            'date' => date('Y-m-d'),
            'pic' => $user->id,
            'time' => date('H:i:s'),
            'name' => $row[1],
            'supplier' => $row[3],
            'source' => $row[2],
            'qty' => $row[4] ? $row[4] : 0,

        ]);
    }

    public function startRow(): int
    {
        return 4;
    }
}
