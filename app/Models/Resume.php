<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    /** @use HasFactory<\Database\Factories\ResumeFactory> */
    use HasFactory;

    protected $fillable = [
        'created_at',
        'updated_at',
        'user_profile_id',
        'resume_template_id',
    ];

    public function resumeTemplate()
    {
        return $this->belongsTo(ResumeTemplate::class, 'resume_template_id');
    }

    public function education()
    {
        return $this->morphedByMany(Education::class, 'source', 'resume_contents', 'resume_id', 'source_id');
    }

    public function userProfile()
    {
        return $this->belongsTo(UserProfile::class, 'user_profile_id');
    }

    public function userLanguages()
    {
        return $this->morphedByMany(UserLanguage::class, 'source', 'resume_contents', 'resume_id', 'source_id');
    }

    public function socialMediaLinks()
    {
        return $this->morphedByMany(SocialMediaLink::class, 'source', 'resume_contents', 'resume_id', 'source_id');
    }

    public function userSkills()
    {
        return $this->morphedByMany(UserSkill::class, 'source', 'resume_contents', 'resume_id', 'source_id');
    }
}
