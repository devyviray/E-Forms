<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Auth;
use App\Department;
use jeremykenedy\LaravelRoles\Models\Role;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Getting list of users
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $users = User::with('roles:name', 'companies:name', 'department:id,name')->orderBy('id', 'desc')->get();
        return $users;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.form',['location' => 'User']);
    }

    /**
     * Show the edit form
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('users.edit',['location' => 'User']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required',
            // 'email' => 'required|unique:users,email',
            'email' => 'required',
            'password' => 'required',
            'position' => 'required',
            'company' => 'required',
            'department' => 'required',
            'role' => 'required'
        ]);
        $user = new User;

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->department_id = $request->input('department');
        $user->position = $request->input('position');

        if($user->save()){
            // Assigning of role
            $user->syncRoles($request->input('role')); 
            // Assigning of companies
            $user->companies()->sync( (array) $request->input('company'));
            
            return ['redirect' => route('users')];
        }
    }

    public function update(Request $request, User $user)
    {
        $validator = $request->validate([
            'id' => 'required',
            'name' => 'required',
            'email' => 'required|unique:users,email,' .$user->id,
            'company' => 'required',
            'department' => 'required',
            'role' => 'required'
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->department_id = $request->input('department');

        if($user->save()){
            $user->roles()->sync( (array) $request->input('role'));
            $user->companies()->sync( (array) $request->input('company'));

            return ['redirect' => route('users')];
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
        $user = User::with('roles', 'companies', 'department')->where('id', $id)->get();

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrfail($id);

        if($user->delete()){
            return $user;
        }
    }

    /**
     * Get Authenticated User
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function authUser()
    {
        $id =  Auth::user()->id;
        $user = User::with('roles:name')
            ->where('id', $id)->get();
        
        return $user;
    }
    
    public function changePassword(Request $request){
        $validator = $request->validate([
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ]);
    
        $user = Auth::user();
        $user->password = bcrypt($request->input('new_password'));
        $user->save();

        return $user;
    }
}   
