<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    /** @use HasFactory<\Database\Factories\InterviewFactory> */
    use HasFactory;

    protected $fillable = [
        'invitation_id',
        'scheduled_at',
        'interview_mode',
        'location',
        'meeting_url',
        'interview_status',
        'interview_result',
        'recruiter_comment',
        'created_at',
        'updated_at',
    ];

    public function invitation()
    {
        return $this->belongsTo(Invitation::class, 'invitation_id');
    }
}
