<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ncn extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Ncnforms';

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'requester', 'position', 'notification', 'date_issuance', 'approver_status', 'notified_status'
    ];

    public function requester()
    {
        return $this->belongsTo('App\User', 'requester_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\User', 'approver_id');
    }
    
    public function notified()
    {
        return $this->belongsTo('App\User', 'notified_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function department()
    {
        return $this->belongsTo('App\Department');
    }
}
