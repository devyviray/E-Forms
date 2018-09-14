<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ccir extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Ccirforms';

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'requester', 'brand_name', 'date_issuance', 'delivery_date'
    ];


    public function requester()
    {
        return $this->belongsTo('App\User', 'requester_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function verifier(){
        return $this->belongsTo('App\User', 'verifier_id');
    }
}
