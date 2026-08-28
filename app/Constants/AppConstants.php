<?php

namespace App\Constants;

class AppConstants
{
    public const PROFICIENCY_LEVELS = ['Beginner', 'Intermediate', 'Advanced', 'Expert'];
    public const PROFILE_VISIBILITY = ['Public', 'Recruiter', 'Private'];
    public const EMPLOYMENT_STATUS = ['Open to Work', 'Open to Internship', 'Employed', 'Not Looking'];
    public const PROGRAMME_LEVELS = ['Diploma', 'Bachelor', 'Master', 'Doctor of Philosophy'];
    public const ENROLLMENT_STATUS = ['Active', 'Graduated', 'Deferred', 'Withdrawn'];
    public const VERIFICATION_STATUS = [
        'PENDING' => 'Pending',
        'ACCEPTED' => 'Accepted',
        'REJECTED' => 'Rejected',
    ];
    public const MEDIA_TYPES = ['image', 'video', 'pdf', 'document'];
    public const EMPLOYMENT_TYPES = ['Internship', 'Full-Time', 'Part-Time', 'Freelance', 'Volunteer'];
    public const INVITATION_STATUS = [
        'PENDING' => 'Pending',
        'ACCEPTED' => 'Accepted',
        'REJECTED' => 'Rejected',
        'EXPIRED' => 'Expired',
        'WITHDRAWN' => 'Withdrawn'
    ];

    // Interview constants
    public const INTERVIEW_MODES = ['Online', 'On-site', 'Phone'];
    public const INTERVIEW_STATUS = [
        'SCHEDULED' => 'Scheduled',
        'COMPLETED' => 'Completed',
        'CANCELLED' => 'Cancelled',
        'RESCHEDULED' => 'Rescheduled',
    ];
    public const INTERVIEW_RESULTS = [
        'PENDING' => 'Pending',
        'PASSED' => 'Passed',
        'FAILED' => 'Failed',
    ];
}
