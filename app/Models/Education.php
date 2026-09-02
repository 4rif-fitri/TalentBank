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
        'cgpa',
        'start_date',
        'end_date',
        'enrollment_status',
        'verification_status',
    ];

    public function semesters()
    {
        return $this->hasMany(Semester::class, 'education_id');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'source', 'source_name', 'source_id');
    }

    public function skills()
    {
        return $this->morphToMany(Skill::class, 'source', 'user_skills')
            ->withPivot('id');
    }
}
