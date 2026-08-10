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
        'user_id',
        'role_id',
    ];

    public $timestamps = false;
}
