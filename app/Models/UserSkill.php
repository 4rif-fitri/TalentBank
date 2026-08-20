<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model
{
    /** @use HasFactory<\Database\Factories\UserSkillFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'source_type',
        'source_id',
        'skill_id',
    ];

    public function source()
    {
        return $this->morphTo();
    }
}
