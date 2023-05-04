<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Illuminate\Support\Collection;
//use hash
use Illuminate\Support\Facades\Hash;
//import start row
use Maatwebsite\Excel\Concerns\withStartRow;


class UserImport implements ToCollection, WithHeadingRow, WithValidation, WithUpserts, withStartRow
{
    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
            User::updateOrCreate(
                [
                    'username' => $row['username'],
                ],
                [
                    'nama' => $row['nama'],
                    'password' => Hash::make($row['password']),
                ]
            );
        }
    }
    public function uniqueBy()
    {
        return ['username'];
    }
    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '*.username' => 'required',
            '*.nama' => 'required',
            '*.password' => 'required',
        ];
    }
}
