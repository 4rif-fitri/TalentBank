<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    /** @use HasFactory<\Database\Factories\EducationFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_profile_id',
        'programme_id',
        'description',
        'field_of_study_id',
        'qualification_id',
        'cgpa',
        'start_date',
        'end_date',
        'enrollment_status',
        'verification_status',
    ];
}
