<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;

class CompanyController extends Controller
{
    /**
    * Display a listing of company with the unique name.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {

        $companies = Company::orderBy('id', 'desc')
        ->get()
        ->unique('name')
        ->values()
        ->all();

        return $companies;
    }

     /**
    * Display a listing of company.
    *
    * @return \Illuminate\Http\Response
    */

    public function companyLocation()
    {
        return  $companies = Company::orderBy('id', 'desc')->get();
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
            'name' => 'required',
            'city' => 'required',
            ]);
            
            $company = new Company;
            $company->name = $request->input('name');
            $company->address = $request->input('city');
            
            if($company->save()){
                return Company::orderBy('id','desc')->get();
            }
    }
        
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $company = Company::findOrfail($id);
        
        return $company;
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
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'city' => 'required',
            ]);
            
            $company->name = $request->input('name');
            $company->address = $request->input('city');
            $company->save();
            
            return $company;
    }
        
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $company = Company::findOrfail($id);
        
        if($company->delete()){
            return $company;
        }
    }
        
}
        