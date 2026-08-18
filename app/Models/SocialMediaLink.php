<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaLink extends Model
{
    /** @use HasFactory<\Database\Factories\SocialMediaLinkFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['user_profile_id', 'social_media_id', 'link'];

    public function socialMedia()
    {
        return $this->belongsTo(SocialMedia::class, 'social_media_id');
    }
}
