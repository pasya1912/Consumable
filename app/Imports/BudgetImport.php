<?php

namespace App\Imports;

use App\Models\Budget;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Illuminate\Support\Collection;
//import start row
use Maatwebsite\Excel\Concerns\WithStartRow;


class BudgetImport implements ToCollection, WithHeadingRow, WithValidation, WithUpserts, WithStartRow
{
    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
            try{
                Budget::create(
                    [
                        'code_item' => $row['code_item'],
                        'category' => $row['category'],
                        'user' => $row['user'],
                        'quota' => $row['budget'],
                    ]);
            }catch(\Exception $e){
                //check if error duplicate entry
                if($e->errorInfo[1] == 1062){
                    //update
                    Budget::where('code_item', $row['code_item'])
                    ->where('user', $row['user'])
                    ->update([
                        'category' => $row['category'],
                        'quota' => $row['budget']
                    ]);
                }


            }

        }

    }
    public function uniqueBy()
    {
        return ['code_item', 'user'];
    }
    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '*.code_item' => 'required',
            '*.user' => 'required',
            '*.category' => 'required',
            '*.budget' => 'required',
        ];
    }

}
