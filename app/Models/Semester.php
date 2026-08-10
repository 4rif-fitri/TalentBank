<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    /** @use HasFactory<\Database\Factories\SemesterFactory> */
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'gpa',
        'session',
        'created_at',
        'updated_at',
    ];
}
