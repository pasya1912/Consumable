<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ItemsImport;
use App\Imports\BudgetImport;
use App\Imports\UserImport;




class importController extends Controller
{
    function item(Request $request)
    {
        //get file then import from uploaded file to collecion

        try{
            $request->validate([
                'file' => 'required|mimes:xls,xlsx'
            ]);
            $file = $request->file('file');
            Excel::import(new ItemsImport, $file);
            return redirect()->back()->with('message', 'Items imported successfully.');
        }
        catch(\Maatwebsite\Excel\Validators\ValidationException $e){
            $error_row= [];
            //append the failure errror row to array
            foreach($e->failures() as $failure){
                //if not exist in array then pus
                if(!in_array($failure->row(),$error_row)){
                    $error_row[] = $failure->row();
                }
            }

            return redirect()->back()->with('message', 'Items imported failed. Error row: '.implode(',',$error_row).' sreason: '.$e->getMessage());
        }


    }
    function budget(Request $request)
    {
        //get file then import from uploaded file to collecion

        try{
            $request->validate([
                'file' => 'required|mimes:xls,xlsx'
            ]);

            $file = $request->file('file');

            Excel::import(new BudgetImport, $file);
            return redirect()->back()->with('message', 'Budget imported successfully.');
        }
        catch(\Maatwebsite\Excel\Validators\ValidationException $e){
            $error_row= [];
            //append the failure errror row to array
            foreach($e->failures() as $failure){
                //if not exist in array then pus
                if(!in_array($failure->row(),$error_row)){
                    $error_row[] = $failure->row();
                }
            }

            return redirect()->back()->with('message', 'Budget import failed. Error row: '.implode(',',$error_row).' sreason: '.$e->getMessage());

        }
        // Read the file into an array

    }
    function user(Request $request)
    {
        //get file then import from uploaded file to collecion

        try{
            $request->validate([
                'file' => 'required|mimes:xls,xlsx'
            ]);

            $file = $request->file('file');

            Excel::import(new UserImport, $file);
            return redirect()->back()->with('message', 'User imported successfully.');
        }
        catch(\Maatwebsite\Excel\Validators\ValidationException $e){
            $error_row= [];
            //append the failure errror row to array
            foreach($e->failures() as $failure){
                //if not exist in array then pus
                if(!in_array($failure->row(),$error_row)){
                    $error_row[] = $failure->row();
                }
            }

            return redirect()->back()->with('message', 'User import failed. Error row: '.implode(',',$error_row).' sreason: '.$e->getMessage());

        }
        // Read the file into an array
    }
}
