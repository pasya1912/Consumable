<?php

namespace App\Imports;

use App\Models\TtInput;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportTtInput implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new TtInput([
            'part_no' => $row[0],
            'date' => $row[1],
            'pic' => $row[1],
            'time' => $row[1],
            'name' => $row[1],
            'qty_stock' => $row[1],
            'qty' => $row[1],

        ]);
    }
}
