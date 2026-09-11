@extends('layouts.internship-layouts')

@section('css')
<style>
    .talent-layout {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }

    .filter-panel {
        width: 350px;
        background-color: white !important;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.05);
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .filter-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: none;
        z-index: 1040;
    }

    .results-panel {
        padding: 1rem;
        background-color: #fff;
    }

    @media (max-width: 768px) {
        .talent-layout {
            display: block;
        }

        .filter-panel {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            max-width: 100vw;
            height: 100vh;
            z-index: 1050;
            border-radius: 0;
            overflow-y: auto;
            transform: translateY(100%);
        }

        body.filter-open .filter-panel {
            transform: translateY(10%);
            border-radius: 14px;
        }

        body.filter-open .filter-overlay {
            display: block;
        }

        body.filter-open {
            overflow: hidden;
        }
    }
</style>
@endsection

@section('content')
<div class="content p-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-lg-row gap-3">
        <h3 class="m-0 fw-bold">Interviews</h3>
    </div>

    <div class="talent-layout">

        <aside class="results-panel rounded card" id="filterPanel">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <ul class="nav nav-tabs">
                    <li data-status="Scheduled" class="nav-item">
                        <button class="nav-link active text-primary">Scheduled</button>
                    </li>
                    <li data-status="Completed" class="nav-item">
                        <button class="nav-link text-black">Completed</button>
                    </li>
                    <li data-status="Cancelled" class="nav-item">
                        <button class="nav-link text-black">Cancelled</button>
                    </li>
                </ul>
            </div>

            <div class="invitation-list p-2 d-flex gap-2 flex-column"></div>

        </aside>

        <div class="filter-panel flex-grow-1">
            <div class="row g-3 " id="shortlistContent">

                <div class="d-flex flex-column border-0 p-3 d-flex justify-content-center align-items-center ">
                    <i class="fa-regular fa-folder-open" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
                    <h4 class="mt-2">No Interview Selected Yet</h4>
                    <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
                        <i class="fa-solid fa-filter"></i>
                        Interview
                    </button>
                </div>

            </div>
        </div>
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

            if(!response) return

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
        let id = $(this).data("id")

        try {
            let response = await xApiInterview.getInterviewById( "{{ route('interviews.getInterviewById',['id' => '__ID__']) }}", id)
            if(!response) return

            currentInterview = response.data;
            let imageUrl = "{{ asset('storage/' . env('ORGANIZATION_LOGO_URL')) }}/" + currentInterview.position.organization.organization_logo
            $("#shortlistContent").html(xinterview.student.mainContent(currentInterview, imageUrl));

        } catch (error) {
            console.error(error);
        }
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
    $(document).on("click", ".invitation-item, .list-item", handleInvitationClick);
</script>
@endsection
