<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    /** @use HasFactory<\Database\Factories\EnrollmentFactory> */
    use HasFactory;

    protected $fillable = [
        'programme_id',
        'user_id',
        'student_email',
        'matric_number',
        'intake_year',
        'graduation_year',
        'cgpa',
        'enrollment_status',
        'verification_status',
        'created_at',
        'updated_at',
    ];

    public function semesters()
    {
        return $this->hasMany(Semester::class, 'enrollment_id');
    }
}
