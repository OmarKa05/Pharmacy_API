<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Medicine;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Company::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'slug' => 'required',
    //     ]);

    //     return Company::create($request->all());
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Company::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $company = Company::find($id);
    //     $company->update($request->all());
    //     return $company;
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Company::destroy($id);
    }

    /**
     * Search for a name.
     */
    public function search(string $name)
    {
        return Company::where('name', 'like' , '%'.$name.'%')->get();
    }
}
