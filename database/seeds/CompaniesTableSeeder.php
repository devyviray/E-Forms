<?php

use Illuminate\Database\Seeder;
use App\Company;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
   
	        $company = Company::create([
                'name' => 'Philippine Foremost Milling Corp.',
                'address' => 'Manila',
        	]);
    }
}
