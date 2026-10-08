<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    /** @use HasFactory<\Database\Factories\InterviewFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'scheduled_at',
        'interview_mode',
        'location',
        'meeting_url',
        'interview_status',
        'interview_result',
        'recruiter_comment',
        'created_at',
        'updated_at',
        'position_id',
        'interviewer_profile_id',
        'interviewee_profile_id',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function interviewer()
    {
        return $this->belongsTo(UserProfile::class, 'interviewer_profile_id');
    }

    public function interviewee()
    {
        return $this->belongsTo(UserProfile::class, 'interviewee_profile_id');
    }
}
