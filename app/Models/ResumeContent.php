<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeContent extends Model
{
    /** @use HasFactory<\Database\Factories\ResumeContentFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'source_type',
        'source_id',
        'resume_id',
    ];
}
