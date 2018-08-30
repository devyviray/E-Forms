<?php

use Illuminate\Database\Seeder;
use App\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Setting::where('email', '=', 'mr@lafilgroup.com')->first() === null) {
	        $settingEmail = Setting::create([
	            'email' => 'mr@lafilgroup.com',
        	]);
	    }

    }
}
