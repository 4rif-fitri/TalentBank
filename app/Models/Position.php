<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    /** @use HasFactory<\Database\Factories\PositionFactory> */
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'user_profile_id',
        'position_title',
        'employment_type',
        'department',
        'work_location',
        'vacancies',
        'description',
        'created_at',
        'updated_at',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function shortlistUsers()
    {
        return $this->belongsToMany(UserProfile::class, 'shortlists', 'position_id', 'user_profile_id');
    }
}
