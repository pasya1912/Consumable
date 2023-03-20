<?php

namespace App\Imports;

use App\Models\Item;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithUpserts;
//import rule
use Illuminate\Validation\Rule;

class ItemsImport implements ToCollection, WithHeadingRow, WithValidation, WithUpserts
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Item::updateOrCreate(
            [
                'code_item' => $row['code_item']
            ],
            [
                'name_item' => $row['nama_item'],
                'area' => $row['area'],
                'lemari' => $row['lemari'],
                'no2' => $row['no2'],
                'satuan' => $row['satuan'],
                'satuan_oca' => $row['satuan_oca'],
                'convert' => $row['convert'],
                'note' => $row['note'],
                'kate' => $row['kate']
            ]);
        }
    }
    public function uniqueBy()
    {
        return 'code_item';
    }

    public function rules(): array
    {
        return [

            '*.nama_item' => 'required',
            '*.area' => 'required',
            '*.lemari' => 'required',
            '*.no2' => 'required',
            '*.satuan' => 'required',
            '*.satuan_oca' => 'required',
            '*.convert' => 'required',
            '*.kate' => 'required',
        ];
    }



}
