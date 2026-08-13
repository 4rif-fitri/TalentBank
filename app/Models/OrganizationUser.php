<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationUser extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationUserFactory> */
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'user_profile_id',
        'role_id',
        'status',
    ];

    public $timestamps = false;

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
