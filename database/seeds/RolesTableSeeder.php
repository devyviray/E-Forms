<?php

use App\User;
use jeremykenedy\LaravelRoles\Models\Role;
use jeremykenedy\LaravelRoles\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	   
		if (Role::where('name', '=', 'Administrator')->first() === null) {
	        $adminRole = Role::create([
	            'name' => 'Administrator',
	            'slug' => 'administrator',
	            'description' => 'Administrator Role',
	            'level' => 7,
        	]);
		}
		
		if (Role::where('name', '=', 'MR')->first() === null) {
	        $adminRole = Role::create([
	            'name' => 'MR',
	            'slug' => 'MR',
	            'description' => 'MR Role',
	            'level' => 6,
        	]);
	    }

    	if (Role::where('name', '=', 'Approver')->first() === null) {
	        $userRole = Role::create([
	            'name' => 'Approver',
	            'slug' => 'approver',
	            'description' => 'Approver Role',
	            'level' => 5,
	        ]);
	    }

    	if (Role::where('name', '=', 'Reviewer')->first() === null) {
	        $userRole = Role::create([
	            'name' => 'Reviewer',
	            'slug' => 'reviewer',
	            'description' => 'Reviewer Role',
	            'level' => 4,
	        ]);
		}
		
		if (Role::where('name', '=', 'Moderator')->first() === null) {
	        $userRole = Role::create([
	            'name' => 'Moderator',
	            'slug' => 'moderator',
	            'description' => 'Moderator Role',
	            'level' => 3,
	        ]);
		}
		
		if (Role::where('name', '=', 'Notified')->first() === null) {
	        $userRole = Role::create([
	            'name' => 'Notified',
	            'slug' => 'notified',
	            'description' => 'Notified Role',
	            'level' => 2,
	        ]);
		}

		if (Role::where('name', '=', 'Requester')->first() === null) {
	        $userRole = Role::create([
	            'name' => 'Requester',
	            'slug' => 'requester',
	            'description' => 'Requester Role',
	            'level' => 1,
	        ]);
		}	
    }

}