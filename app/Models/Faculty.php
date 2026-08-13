<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    /** @use HasFactory<\Database\Factories\FacultyFactory> */
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'faculty_name',
        'faculty_code',
        'created_at',
        'updated_at',
    ];

    public function programmes()
    {
        return $this->hasMany(Programme::class, 'faculty_id');
    }
}
