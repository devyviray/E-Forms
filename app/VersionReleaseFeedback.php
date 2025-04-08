<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VersionReleaseFeedback extends Model
{
    // Fillable fields to prevent mass assignment
    protected $fillable = ['user_id', 'version_release_id', 'feedback'];

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
