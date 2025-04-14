<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Drdr extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditabletable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'drdrforms';

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'company',
    ];


    public function reviewer(){
        return $this->belongsTo('App\User', 'reviewer_id');
    }

    public function approver(){
        return $this->belongsTo('App\User', 'approver_id');
    }

    public  function requester()
    {
        return $this->belongsTo('App\User', 'requester_id');
    }

    public function distributed()
    {
        return $this->belongsTo('App\User', 'distributed_id');
    }

    public function company()
    {
        return $this->belongsTO('App\Company');
    }

    public function drdrCopyholders(){
        return $this->hasMany('App\DrdrformsCopyholder', 'form_id');
    }
}
