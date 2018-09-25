<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address',
    ];

    public function users(){
        return $this->belongsToMany('App\User');
    }

    public function companies(){
        return $this->belongsToMany('App\Company_user');
    }
}
