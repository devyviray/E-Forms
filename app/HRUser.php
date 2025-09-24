<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class HRUser extends Model
{
    protected $connection = 'hr_portal';
    protected $table = 'users';

}
