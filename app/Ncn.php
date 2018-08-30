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

    public function requester(){
        return $this->belongsTo('App\User', 'requester_id');
    }
}
