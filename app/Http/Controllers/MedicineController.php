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
            'quantity' => 'required',
            'expiry_date' => 'required|nullable|date',
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
        // Find the medicine with the given name
        $medicine = Medicine::where('name', 'like', '%' . $name . '%')->first();
        
        if (!$medicine) {
            // If no medicine is found with the given name, return an empty collection
            return Medicine::with('company')->get();
        }
        
        // Retrieve the scientific composition of the found medicine
        $scientificComposition = $medicine->scientific_composition;
        
        // Find all medicines with the same scientific composition
        return Medicine::where('scientific_composition', $scientificComposition)
                       ->with('company')
                       ->get();
    }
}