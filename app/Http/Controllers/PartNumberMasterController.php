<?php

namespace App\Http\Controllers;

use App\Models\TmPartNumber;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PartNumberMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('layouts.partNumber-master');
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'part_name' => 'required',
            'part_number' => 'required|unique:tm_part_numbers|min:11|max:11',
            'qty_limit' => 'required'
        ]);

        TmPartNumber::create([
            'part_name' => $request->part_name,
            'part_number' => $request->part_number,
            'qty_limit' => $request->qty_limit,
        ]);
        

        return redirect()->back()->with('success', 'Part Number created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function getData()
    {
        $input = TmPartNumber::all();
        return DataTables::of($input)
                ->toJson();
    }
}
