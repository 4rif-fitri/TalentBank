<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    /** @use HasFactory<\Database\Factories\UserProfileFactory> */
    use HasFactory;

    public $fillable = [
        'name',
        'email',
        'user_id',
        'about',
        'headline',
        'location',
        'phone_no',
        'profile_image',
        'cover_image',
        'employment_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_user', 'user_profile_id', 'organization_id');
    }

    public function organizationUsers()
    {
        return $this->hasMany(OrganizationUser::class, 'user_profile_id');
    }

    public function activeProgramme()
    {
        return $this->hasOneThrough(Programme::class, Enrollment::class, 'user_profile_id', 'id', 'id', 'programme_id')
            ->where('enrollments.enrollment_status', 'Active');
    }
}
