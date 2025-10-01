<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HRISUser extends Model
{
    protected $table = "users";
    protected $connection = "hr_portal";
}