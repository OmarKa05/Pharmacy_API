<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Medicine::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'scientific_composition' => 'required',
            'price' => 'required',
            'company_id' => 'required',
            'shelf' => 'required',
            'quantity' => 'required'
        ]);

        return Medicine::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Medicine::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $medicine = Medicine::find($id);
        $medicine->update($request->all());
        return $medicine;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Medicine::destroy($id);
    }

    /**
     * Search for a name.
     */
    public function search(string $name)
    {
        return Medicine::where('name', 'like' , '%'.$name.'%')->with('company')->get();
    }
}