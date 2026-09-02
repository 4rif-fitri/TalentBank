<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    /** @use HasFactory<\Database\Factories\LikeFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'liker_user_profile_id',
        'liked_user_profile_id',
    ];

    public function likedUserProfile()
    {
        return $this->belongsTo(UserProfile::class, 'liked_user_profile_id');
    }
}
