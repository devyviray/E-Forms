<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the application ccir.
     *
     * @return \Illuminate\Http\Response
     */
    public function ccir_index()
    {
        return view('ccir.index');
    }
    
    /**
     * Show the application company.
     *
     * @return \Illuminate\Http\Response
     */
    public function companies_index()
    {
        return view('companies.index');
    }

     /**
     * Show the application ddr.
     *
     * @return \Illuminate\Http\Response
     */
    public function ddr_index()
    {
        return view('ddr.index');
    }

    /**
     * Show the application departments.
     *
     * @return \Illuminate\Http\Response
     */
    public function departments_index()
    {
        return view('departments.index');
    }

    /**
     * Show the application drdr.
     *
     * @return \Illuminate\Http\Response
     */
    public function drdr_index()
    {
        return view('drdr.index');
    }

    /**
     * Show the application ncn.
     *
     * @return \Illuminate\Http\Response
     */
    public function ncn_index()
    {
        return view('ncn.index');
    }

    /**
     * Show the application permissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function permissions_index()
    {
        return view('permissions.index');
    }

    /**
     * Show the application roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function roles_index()
    {
        return view('roles.index');
    }

     /**
     * Show the application users.
     *
     * @return \Illuminate\Http\Response
     */
    public function users_index()
    {
        return view('users.index');
    }
}
