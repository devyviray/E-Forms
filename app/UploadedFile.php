<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UploadedFile extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'uploaded_files';
}
