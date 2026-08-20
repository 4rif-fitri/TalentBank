<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    /** @use HasFactory<\Database\Factories\SkillFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'skill_name',
        'skill_category',
        'icon_class_name',
    ];
}
