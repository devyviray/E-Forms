<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VersionRelease extends Model
{
    // Fillable fields to prevent mass assignment
    protected $fillable = ['version', 'release_date', 'user_id'];

    // Relationships
    public function releasenotes()
    {
        return $this->hasMany(VersionReleaseNote::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(VersionReleaseFeedback::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);  // Foreign key relation to users
    }
}
