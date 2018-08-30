<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Company;
use App\User;

class UsersTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testCompanyReviewer()
    {
        $reviewer_user = User::whereHas('roles', function($q) {
            $q->where('role_id', 3);  // reviewer  _
        })->whereHas('companies', function($q) {
            $q->where('company_id',1);
        })->get();

        echo json_encode($reviewer_user,JSON_PRETTY_PRINT);
    }
}
