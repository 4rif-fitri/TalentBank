<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustrySector extends Model
{
    /** @use HasFactory<\Database\Factories\IndustrySectorFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];
}
