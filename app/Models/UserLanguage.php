<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLanguage extends Model
{
    /** @use HasFactory<\Database\Factories\UserLanguageFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_profile_id',
        'language_id',
        'proficiency_level',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
