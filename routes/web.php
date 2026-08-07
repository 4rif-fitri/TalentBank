<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\internshipController;

Route::get('/', [internshipController::class, "index"]);
