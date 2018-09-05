<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ddr extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Ddrforms';

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'requester', 'position', 'date_needed', 'date_requester', 'status'
    ];

    public function requester(){
        return $this->belongsTo('App\User', 'requester_id');
    }
    
    public function approver(){
        return $this->belongsTo('App\User', 'approver_id');
    }

    public function company(){
        return $this->belongsTo('App\Company', 'company_id');
    }
    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function ddrLists()
    {
        return $this->hasMany('App\DdrformsList', 'form_id');
    }
}
