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

    public function recruiterPosition()
    {
        return view("pages.recruiter.position");
    }

    public function recruiterProfiles()
    {
        return view("pages.recruiter.profiles");
    }
    public function recruiterLikeTalent()
    {
        return view("pages.recruiter.likeTalent");
    }
    public function recruiterInvitation()
    {
        return view("pages.recruiter.invitation");
    }
    public function recruiterInterview()
    {
        return view("pages.recruiter.interview");
    }
    public function recruiterJobOffer()
    {
        return view("pages.recruiter.jobOffer");
    }
    public function recruiterHiredTalent()
    {
        return view("pages.recruiter.hiredTalent");
    }
    public function recruiterMessage()
    {
        return view("pages.recruiter.hiredTalent");
    }
    public function recruiterSetting()
    {
        return view("pages.recruiter.setting");
    }
}
