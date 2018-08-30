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
}
