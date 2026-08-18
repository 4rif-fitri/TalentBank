<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationFactory> */
    use HasFactory;

    protected $fillable = [
        'company_name',
        'ssm_number',
        'industry_category_id',
        'address',
        'postcode',
        'city',
        'state',
        'website',
        'description',
        'company_email',
        'company_phone',
        'industry_sector_id',
        'organization_type_id',
        'created_at',
        'updated_at',
    ];

    public function organizationUsers()
    {
        return $this->hasMany(OrganizationUser::class, 'organization_id');
    }
}
