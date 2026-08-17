<?php

namespace App\Http\Controllers;

class internshipController extends Controller
{
    public function index()
    {
        // return view("pages.index");
    }

    public function invitations()
    {
        return view("pages.invitations");
    }

    public function profile()
    {

        return view("pages.profile");
    }

    public function interviews()
    {
        return view("pages.interviews");
    }

    public function jobOffers()
    {
        return view("pages.jobOffers");
    }

    public function messages()
    {
        return view("pages.messages");
    }

    public function settings()
    {
        return view("pages.settings");
    }
}
