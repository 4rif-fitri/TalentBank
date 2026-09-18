<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    /** @use HasFactory<\Database\Factories\ResumeFactory> */
    use HasFactory;

    protected $fillable = [
        'created_at',
        'updated_at',
        'user_profile_id',
    ];

    public function education()
    {
        return $this->morphedByMany(Education::class, 'source', 'resume_contents', 'resume_id', 'source_id');
    }
}
