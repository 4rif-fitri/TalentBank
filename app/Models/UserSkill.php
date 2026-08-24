<?php

namespace App\Models;
use App\Models\Skill;

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

    public function skill(){
        return $this->belongsTo(Skill::class, 'skill_id');
    }
}
