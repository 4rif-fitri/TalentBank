<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeTemplate extends Model
{
    /** @use HasFactory<\Database\Factories\ResumeTemplateFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'template_file_name',
        'thumbnail_file_name',
    ];
}
