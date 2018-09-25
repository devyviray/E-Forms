<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_user';

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'user_id',
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
    
}
