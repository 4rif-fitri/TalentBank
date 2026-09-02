<?php

namespace App\Http\Controllers;

class internshipController extends Controller
{
    public function index()
    {
        $roles = session('roles', []);

        if (in_array('Student', $roles)) {
            return view('pages.student.index');
        }

        if (in_array('Recruiter', $roles)) {
            return view('pages.recruiter.index');
        }
    }
    public function studentIndex()
    {
        return view("pages.student.index");
    }
    public function invitations()
    {
        return view("pages.student.invitations");
    }

    public function studentView()
    {
        return view("pages.student.profile");
    }

    public function profile()
    {
        return view("pages.student.profile");
    }

    public function interviews()
    {
        return view("pages.student.interviews");
    }

    public function jobOffers()
    {
        return view("pages.student.jobOffers");
    }

    public function messages()
    {
        return view("pages.student.messages");
    }

    public function settings()
    {
        return view("pages.student.settings");
    }

    // ==== Recruiter ====
    public function recruiterIndex()
    {
        return view("pages.recruiter.index");
    }

    public function recruiterShortlists()
    {
        return view("pages.recruiter.shortlists");
    }

    public function recruiterTalents()
    {
        return view("pages.recruiter.talents");
    }
    public function recruiterSavedTalent()
    {
        return view("pages.recruiter.savedTalent");
    }
    public function recruiterInvitations()
    {
        return view("pages.recruiter.invitations");
    }
    public function recruiterInterviews()
    {
        return view("pages.recruiter.interviews");
    }
    public function recruiterJobOffers()
    {
        return view("pages.recruiter.jobOffers");
    }
    public function recruiterHiredTalent()
    {
        return view("pages.recruiter.hiredTalent");
    }
    public function recruiterMessages()
    {
        return view("pages.recruiter.hiredTalent");
    }
    public function recruiterSettings()
    {
        return view("pages.recruiter.settings");
    }
}
