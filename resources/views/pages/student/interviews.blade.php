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

            <div class="invitation-list"></div>

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

<div class="filter-overlay" onclick="toggleFilter()"></div>
@endsection

@section('script')
<script type="module">
    let currentInterviews = null
    let currentInterview = null
    let currentStatus = "Scheduled"

    function getInterviewsByStatusAndIntervieweeId(status){
        $.ajax({
            url: "{{ route('interviews.getInterviewsByStatusAndIntervieweeId',['status' => '__STATUS__']) }}".replace("__STATUS__", status),
            type: "GET",
            data: { status:status },
            success: function (response) {
                xdebug.log(status, response.data);
                currentInterviews = response.data

                $(".invitation-list").empty()
                currentInterviews.forEach(inv =>{
                    let imageUrl = "{{ asset('storage/' . env('ORGANIZATION_LOGO_URL')) }}/" + inv.position.organization.organization_logo
                    $(".invitation-list").append(xinterview.student.sideList(inv, imageUrl))
                });
            },
            error: function (xhr) {
                xdebug.log(xhr.responseJSON.message)
            }
        });
    }

    function handleInvitationClick() {
        let id = $(this).data("id")

        let url = "{{ route('interviews.getInterviewById',['id' => '__ID__']) }}"
        url = url.replace("__ID__", id)

        $.ajax({
            type: "GET",
            url,
            success: function (response) {
                xdebug.log("getInterviewById", response.data);
                currentInterview = response.data;
                let imageUrl = "{{ asset('storage/' . env('ORGANIZATION_LOGO_URL')) }}/" + currentInterview.position.organization.organization_logo
                $("#shortlistContent").html(xinterview.student.mainContent(currentInterview, imageUrl));
            },
            error: function (xhr) {
                xdebug.log(xhr.responseJSON.message)
            }
        });
    }

    function handleFilterToggle() {
        $(".nav-item button").removeClass("active text-primary").addClass("text-body");
        $(this).find("button").removeClass("text-body").addClass("active text-primary");
        let status = $(this).data("status")

        if(status == currentStatus) return
        currentStatus = status

        getInterviewsByStatusAndIntervieweeId(status)
    }

    $(document).on("click", ".nav-item", handleFilterToggle);
    $(document).on("click", ".invitation-item", handleInvitationClick);
</script>
@endsection
