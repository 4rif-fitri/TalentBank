<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    /** @use HasFactory<\Database\Factories\ProgrammeFactory> */
    use HasFactory;

    protected $fillable = [
        'faculty_id',
        'programme_name',
        'programme_code',
        'programme_level',
        'duration_years',
        'status',
        'organization_id',
        'created_at',
        'updated_at',
    ];

    public function education()
    {
        return $this->hasMany(Education::class, 'programme_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
