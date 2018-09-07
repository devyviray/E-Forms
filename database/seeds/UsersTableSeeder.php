<?php

use App\User;
use jeremykenedy\LaravelRoles\Models\Role;
use jeremykenedy\LaravelRoles\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

		$adminRole 			= Role::where('name', '=', 'Administrator')->first();
		$MRRole 			= Role::where('name', '=', 'MR')->first();
		$approverRole 		= Role::where('name', '=', 'Approver')->first();
		$reviewerRole 		= Role::where('name', '=', 'Reviewer')->first();
		$requesterRole 		= Role::where('name', '=', 'Requester')->first();
		$notifiedRole 		= Role::where('name', '=', 'Notified')->first();
		$moderatorRole 		= Role::where('name', '=', 'Moderator')->first();
		$permissions 		= Permission::all();

	    /**
	     * Add Users
	     *
	     */
        if (User::where('email', '=', 'demetrio.viray@lafilgroup.com')->first() === null) {

	        $newUser = User::create([
	            'name' => 'Administrator',
	            'email' => 'demetrio.viray@lafilgroup.com',
				'password' => bcrypt('password'),
				'position' => 'CIO',
				'department_id' => 1,
	        ]);

	        $newUser->attachRole($adminRole);
			foreach ($permissions as $permission) {
				$newUser->attachPermission($permission);
			}

		}
		
		if (User::where('email', '=', 'mr@lafilgroup.com')->first() === null) {

	        $newUser = User::create([
	            'name' => 'MR',
	            'email' => 'tqmmnl-ilogroup@lafilgroup.com',
				'password' => bcrypt('password'),
				'department_id' => 1,
				'position' => 'Team '
	        ]);

	        $newUser->attachRole($MRRole);
			foreach ($permissions as $permission) {
				$newUser->attachPermission($permission);
			}

        }

        if (User::where('email', '=', 'approver@yahoo.com')->first() === null) {

	        $newUser = User::create([
	            'name' => 'Approver',
	            'email' => 'approver@yahoo.com',
				'password' => bcrypt('password'),
				'department_id' => 1,
				'position' => 'Web Developer'
	        ]);

	        $newUser;
	        $newUser->attachRole($approverRole);

        }

		if (User::where('email', '=', 'reviewer@yahoo.com')->first() === null) {

	        $newUser = User::create([
	            'name' => 'Reviewer',
	            'email' => 'reviewer@yahoo.com',
				'password' => bcrypt('password'),
				'department_id' => 2,
				'position' => 'HR'
	        ]);

	        $newUser;
	        $newUser->attachRole($reviewerRole);

		}
		
		
		if (User::where('email', '=', 'requester@yahoo.com')->first() === null) {

	        $newUser = User::create([
	            'name' => 'Requester',
	            'email' => 'requester@yahoo.com',
				'password' => bcrypt('password'),
				'department_id' => 3,
				'position' => 'QA'
	        ]);

	        $newUser;
	        $newUser->attachRole($requesterRole);

		}
		
		if (User::where('email', '=', 'notified@yahoo.com')->first() === null) {

	        $newUser = User::create([
	            'name' => 'Notified',
	            'email' => 'notified@yahoo.com',
				'password' => bcrypt('password'),	
				'department_id' => 4,
				'position' => 'Maintenance'
	        ]);

	        $newUser;
	        $newUser->attachRole($notifiedRole);

		}

		if (User::where('email', '=', 'moderator@yahoo.com')->first() === null) {

	        $newUser = User::create([
	            'name' => 'Moderator',
	            'email' => 'moderator@yahoo.com',
				'password' => bcrypt('password'),
				'department_id' => 5,
				'position' => 'Tech writer'
	        ]);

	        $newUser;
	        $newUser->attachRole($moderatorRole);

        }		

    }
}