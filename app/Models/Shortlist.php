<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shortlist extends Model
{
    /** @use HasFactory<\Database\Factories\ShortlistFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'position_id',
        'user_profile_id',
    ];
}
