@extends('layouts.internship-layouts')
@php
$list = [
['status' => 'Scheduled', 'class' => 'active'],
['status' => 'Completed', 'class' => ''],
['status' => 'Cancelled', 'class' => ''],
];
@endphp
@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/internship-assets/style/student.css') }}">

<div class="content p-4 page-container">

    <x-atom.page-header title="Interviews" />

    <div class="talent-layout">

        <aside class="results-panel" id="filterPanel">

            <x-molecule.nav-tabs :list="$list" />

            <div class="invitation-list p-2 d-flex gap-2 flex-column"></div>
        </aside>

        <x-atom.interview-detail panel="std" />
    </div>
</div>

<div class="filter-overlay"></div>
@endsection

@section('script')
<script type="module">
    let currentInterviews = null
    let currentInterview = null
    let currentStatus = "Scheduled"

    async function getInterviewsByStatusAndIntervieweeId(status){
        try {
            let response = await xApiInterview.getInterviewsByStatusAndIntervieweeId(
                "{{ route('interviews.getInterviewsByStatusAndIntervieweeId') }}",
                status
            )

            if (!response) return

            currentInterviews = response.data

            $(".invitation-list").empty()
            currentInterviews.forEach(inv => {
                let imageUrl = "{{ asset('storage/' . env('ORGANIZATION_LOGO_URL')) }}/" + inv.position.organization.organization_logo
                $(".invitation-list").append(xinterview.student.sideList(inv, imageUrl))
            });

        } catch (error) {
            console.error(error);
        }
    }

    async function handleInvitationClick() {

        const id = $(this).data("id");

        if (!id) return;

        window.interviewDetail
            .setRole("student")
            .load(id);

    }

    function handleFilterToggle() {
        $(".nav-item button").removeClass("active text-primary").addClass("text-body");
        $(this).find("button").removeClass("text-body").addClass("active text-primary");
        let status = $(this).data("status")

        if(status == currentStatus) return
        currentStatus = status

        getInterviewsByStatusAndIntervieweeId(status)
    }

    getInterviewsByStatusAndIntervieweeId("Scheduled")
    $(document).on('click', '.btn-toggle-filter, .shortlist-overlay, .filter-overlay, .list-item', toggle);
    $(document).on("click", ".nav-item", handleFilterToggle);
    $(document).on("click", ".list-item", handleInvitationClick);
</script>
@endsection
