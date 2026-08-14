<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldOfStudy extends Model
{
    /** @use HasFactory<\Database\Factories\FieldOfStudyFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    public $timestamps = false;
}
