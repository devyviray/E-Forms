<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;

class Setting extends Model implements Auditable
{
    use Notifiable, Auditable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Settings';
    
}
