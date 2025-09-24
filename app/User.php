<?php

namespace App;

use App\Models\HRUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class User extends Authenticatable implements AuditableContract
{
    use Notifiable, \OwenIt\Auditing\Auditable;
    use HasRoleAndPermission;
    protected $appends = ['hr_user_id'];
    protected $with = ['hrUser'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email', 
        'password', 
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function companies() {
        return $this->belongsToMany('App\Company');
    }

    public function department(){

        return $this->belongsTo('App\Department');
    } 
    public function hrUser()
    {
        return $this->belongsTo(HRUser::class,'email','email');
    }
    public function getHrUserIdAttribute()
    {
        return $this->hrUser->id ?? null;
    }

}
