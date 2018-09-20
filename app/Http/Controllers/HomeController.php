<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

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
        if(Auth::user()->hasRole('administrator') || Auth::user()->hasRole('mr')){
            return view('home', ['location' => 'Dashboard']);
        } return view('home-user', ['location' => 'Dashboard']);
    }

    /**
     * Show the application ccir.
     *
     * @return \Illuminate\Http\Response
     */
    public function ccir_index()
    {
        return view('ccir.index', ['location' => 'Customer Complaint Report']);
    }
    
    /**
     * Show the application company.
     *
     * @return \Illuminate\Http\Response
     */
    public function companies_index()
    {
        return view('companies.index', ['location' => 'Company']);
    }

     /**
     * Show the application ddr.
     *
     * @return \Illuminate\Http\Response
     */
    public function ddr_index()
    {
        return view('ddr.index', ['location' => 'Document Distribution Request']);
    }

    /**
     * Show the application departments.
     *
     * @return \Illuminate\Http\Response
     */
    public function departments_index()
    {
        return view('departments.index', ['location' => 'Department']);
    }

    /**
     * Show the application drdr.
     *
     * @return \Illuminate\Http\Response
     */
    public function drdr_index()
    {
        return view('drdr.index', ['location' => 'Document Review & Document Request'] );
    }

    /**
     * Show the application ncn.
     *
     * @return \Illuminate\Http\Response
     */
    public function ncn_index()
    {
        return view('ncn.index', ['location' => 'Non Conformance Notification']);
    }

    /**
     * Show the application permissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function permissions_index()
    {
        return view('permissions.index',  ['location' => 'Permission']);
    }

    /**
     * Show the application roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function roles_index()
    {
        return view('roles.index', ['location' => 'Role']);
    }

     /**
     * Show the application users.
     *
     * @return \Illuminate\Http\Response
     */
    public function users_index()
    {
        return view('users.index', ['location' => 'User']);
    }
}
