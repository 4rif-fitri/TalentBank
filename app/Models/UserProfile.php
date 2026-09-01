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

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'organization_users', 'user_profile_id', 'role_id')
            ->wherePivot('status', 1);
    }

    public function organizationUsers()
    {
        return $this->hasMany(OrganizationUser::class, 'user_profile_id');
    }

    public function programmes()
    {
        return $this->hasManyThrough(Programme::class, Education::class, 'user_profile_id', 'id', 'id', 'programme_id');
    }

    public function activeProgrammes()
    {
        return $this->hasManyThrough(Programme::class, Education::class, 'user_profile_id', 'id', 'id', 'programme_id')
            ->where('education.enrollment_status', 'Active');
    }

    public function socialMediaLinks()
    {
        return $this->hasMany(SocialMediaLink::class, 'user_profile_id');
    }

    public function userLanguages()
    {
        return $this->hasMany(UserLanguage::class, 'user_profile_id');
    }

    public function skills()
    {
        return $this->morphToMany(Skill::class, 'source', 'user_skills')
            ->withPivot('id');
    }
}
