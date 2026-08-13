<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class internshipController extends Controller
{
    public function index()
    {
        return view("internship.index");
    }

    public function education()
    {
        return view("internship.education");
    }

    public function profile()
    {
        return view("internship.profile");
    }

    public function experience()
    {
        return view("internship.experience");
    }

    public function destroy(string $id)
    {
    }
}
