<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VersionReleaseNote extends Model
{
    protected $fillable = ['version_release_id', 'type', 'description', 'user_id'];

        // Relationships
        public function versionrelease()
        {
            return $this->belongsTo(VersionRelease::class);  // Foreign key relation to version_releases
        }
    
        public function user()
        {
            return $this->belongsTo(User::class);  // Foreign key relation to users
        }
    }
