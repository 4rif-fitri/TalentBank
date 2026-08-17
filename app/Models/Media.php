<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    /** @use HasFactory<\Database\Factories\MediaFactory> */
    use HasFactory;

    protected $fillable = [
        'uploaded_by_user_id',
        'source_name',
        'source_id',
        'media_type',
        'file_name',
        'title',
        'description',
        'created_at',
        'updated_at',
    ];
}
