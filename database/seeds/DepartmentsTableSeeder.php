<?php

use Illuminate\Database\Seeder;
use App\Department;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Department::where('name', '=', 'Information Technology')->first() === null) {
	        $departmentName = Department::create([
	            'name' => 'Information Technology',
        	]);
	    }
    }
}
