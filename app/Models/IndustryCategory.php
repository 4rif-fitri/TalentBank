<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryCategory extends Model
{
    /** @use HasFactory<\Database\Factories\IndustryCategoryFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];
}
