@extends('layouts.internship-layouts')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/recruiter.css') }}">

<div class="content p-4 page-content">

    <x-atom.page-header title="Job Offers" />

    <div class="shortlist-layout">

        <x-molecule.job-offer-list />
        <x-atom.job-offer-detail />

    </div>
</div>
<div class="shortlist-overlay toggleFilter"></div>

<x-modals.job-offer-modal />
<x-active-educations-modal />

@endsection

@section('script')
<script type="module">
    let currentStatus = "Pending"
    let currentJobOffer
    let currentEducation


    $(document).ready(function(){
        interviewList.load("Pending")
    })

    $(document).on("click", ".offer-tab", function () {
        let currentStatus = $(this).data("status")
        $(".offer-tab").removeClass("active")
        $(this).addClass("active")

        let status = $(this).data("status")
        interviewList.load(status)
    })

    $(document).on("click", ".list-item", function(){
        let id = $(this).data("id")
        interviewDetail.load(id)
    })

    $(document).on("click", "#btnEditJobOffer", function(){
        let id = $(this).data("id")
        jobOfferModal.openUpdate(interviewDetail.currentJobOffer)
    })

    $(document).on("click", "#btnUpdateJobOffer", function(){
        let id = interviewDetail.currentJobOffer.id
        jobOfferModal.update(id)
    })

    $(document).on("click", "#btnWithdrawJobOffer", function(){
        let id = $(this).data("id")
        jobOfferModal.withdraw(id)
    })

    $(document).on("click", ".btnSeeMore", function(){
       interviewDetail.education()
    })

    $(document).on('click', '.btn-toggle-filter, .shortlist-overlay', toggle);
</script>
@endsection
