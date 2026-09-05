@extends('layouts.internship-layouts')

@section('css')
<style>
    .icon-img {
        background-size: cover;
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
    }

    .shortlist-layout {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }

    .shortlist-sidebar {
        width: 400px;
        background: #fff;
        height: 80vh !important;
        overflow-y: auto;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.05);
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .shortlist-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: none;
        z-index: 1040;
    }

    @media (max-width: 1000px) {
        .shortlist-layout {
            display: block;
        }

        .shortlist-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 380px;
            max-width: 85vw;
            height: 100vh !important;
            z-index: 1050;
            border-radius: 0;
            overflow-y: auto;
            transform: translateX(-100%);
        }

        body.filter-open .shortlist-sidebar {
            transform: translateX(0);
        }

        body.filter-open .shortlist-overlay {
            display: block;
        }

        body.filter-open {
            overflow: hidden;
        }
    }

    .shortlist-item:hover {
        border: 1px solid #6d7eca !important;
        color: #6d7eca;

        small {
            color: #6d7eca !important;
        }

    }

    .shortlist-item.active {
        border: 1px solid #5267c4 !important;
        color: #5267c4;

        small {
            color: #5267c4 !important;
        }
    }
</style>
@endsection

@section('content')
<div class="content p-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-lg-row gap-3">
        <h3 class="m-0 fw-bold">Interviews</h3>
        <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
            <i class="fa-solid fa-filter"></i>
            Upcoming Interview
        </button>
    </div>

    <div class="shortlist-layout">

        <aside class="shortlist-sidebar" id="listContainer">

            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
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
                    <li data-status="Rescheduled" class="nav-item">
                        <button class="nav-link text-black">Rescheduled</button>
                    </li>
                </ul>
            </div>

            <div id="shortlistList"></div>
        </aside>

        <div class="shortlist-content bg-body flex-grow-1 p-2 rounded card">
            <div class="row g-3" id="shortlistContent">
                <div class="card shadow-sm border-0 p-3 d-flex justify-content-center align-items-center ">
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

<div class="shortlist-overlay toggleFilter"></div>


@endsection

@section('script')
<script type="module">
    function toggleFilter() {
        document.body.classList.toggle('filter-open');
    }

    $(document).on('click', '.btn-toggle-filter, .shortlist-overlay', function () {
        toggleFilter();
    });


    let url = "{{ route('interviews.getInterviewsBySenderId', ['id' => '__ID__' ]) }}"
    url = url.replace("__ID__", 1)

    $.ajax({
        url,
        type: "GET",
        success: function (response) {
            // console.log("getInterviewsBySenderId", response)

            $("#shortlistList").empty()
            let interviews = response.data
            interviews.forEach(interview => {
                $("#shortlistList").append(intervieww.sidebar(interview))
            });

        },
        error: function (xhr) {
            console.error(xhr)

        }
    });


    function getInterviewById(id) {
        let url = "{{ route('interviews.getInterviewById', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", id);

        return $.ajax({
            url: url,
            type: "GET"
        });
    }

    function getEducationByUserProfileId(id) {
        let url = "{{ route('education.getEducationByUserProfileId', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", id);

        return $.ajax({
            url: url,
            type: "GET"
        });
    }

    async function handleSelectedInterview() {
        let id = $(this).data('id');

        try {
            let interviewDetail = await getInterviewById(id);
            let receiverId = interviewDetail.data.invitation.receiver.id
            let listEducationReceiver = await getEducationByUserProfileId(receiverId);

            console.log("Interview:", interviewDetail);
            console.log("Education:", listEducationReceiver);

            $(".shortlist-content").empty();
            $(".shortlist-content").append(intervieww.mainContent(interviewDetail.data, listEducationReceiver.data));

        } catch (xhr) {
            console.error(xhr);
        }
    }

    $(document).on("click", ".shortlist-item", handleSelectedInterview)

    function completeInterview(id) {
        let url = "{{ route('interviews.completeInterview',['id' => '__ID__']) }}"
        url = url.replace("__ID__", id)

        let data = {
            '_token': $('meta[name="csrf-token"]').attr("content"),
            '_method': "PUT"
        }

        $.ajax({
            type: "POST",
            url,
            data,
            success: function (response) {
                debug.log("completeInterview", response.data);
            },
            error: function (xhr) {
                debug.error(xhr.responseJSON.message)
            }
        });
    }

    function cancelInterview(id) {
        let url = "{{ route('interviews.cancelInterview',['id' => '__ID__']) }}"
        url = url.replace("__ID__", id)

        let data = {
            '_token': $('meta[name="csrf-token"]').attr("content"),
            '_method': "PUT"
        }

        $.ajax({
            type: "POST",
            url,
            data,
            success: function (response) {
                debug.log("cancelInterview", response.data);
            },
            error: function (xhr) {
                debug.error(xhr.responseJSON.message)
            }
        });
    }

    function getInterviewsBySenderId() {

        $.ajax({
            url: "{{ route('interviews.getInterviewsBySenderId') }}",
            type: "GET",
            success: function (response) {
                debug.log("getInterviewsBySenderId", response.data);
            },
            error: function (xhr) {
                console.error(xhr.responseJSON.message)
            }
        });
    }

    function update(id) {
        url = "{{ route('interviews.update',['id' => '__ID__' ]) }}"
        url = url.replace("__ID__", id)

        let data = {
            "_token": $('meta[name="csrf-token"]').attr("content"),
            "_method": "PUT",
            "scheduled_at": "2026-9-30 05:07:17",
            "interview_mode": "Online",
            "location": "",
            "meeting_url": "http://127.0.0.1:8000/recruiter/invitations",
            "recruiter_comment": "!!!",
            "interview_result": "Passed",
        }

        $.ajax({
            url,
            data,
            method: "POST",
            success: function (response) {
                debug.log("update", response.data);
            },
            error: function (xhr) {
                console.error(xhr.responseJSON.message)
            }
        });
    }

    function store(id) {
        let data = {
            "_token": $('meta[name="csrf-token"]').attr("content"),
            "scheduled_at": "2026-9-30 05:07:17",
            "interview_mode": "Online",
            "location": "",
            "meeting_url": "http://127.0.0.1:8000/recruiter/invitations",
            "recruiter_comment": "!!!",
            "interview_result": "Passed",
            "invitation_id": id
        }

        $.ajax({
            url: "{{ route('interviews.store') }}",
            data,
            method: "POST",
            success: function (response) {
                debug.log("store", response.data);
            },
            error: function (xhr) {
                console.error(xhr.responseJSON.message)
            }
        });
    }

    function getInterviewsByStatus(status) {
        let url = "{{ route('interviews.getInterviewsByStatus',['status' => '__STATUS__']) }}"
        url = url.replace("__STATUS__", status)

        $.ajax({
            type: "GET",
            url,
            success: function (response) {
                debug.log("getInterviewsByStatus", response.data);
                let interviews = response.data
                $("#shortlistList").empty()
                interviews.forEach(interview => {
                    $("#shortlistList").append(intervieww.sidebar(interview))
                });

            },
            error: function (xhr) {
                debug.error(xhr.responseJSON.message)
            }
        });
    }

    function ggetInterviewById(id) {
        let url = "{{ route('interviews.getInterviewById',['id' => '__ID__']) }}"
        url = url.replace("__ID__", id)

        $.ajax({
            type: "GET",
            url,
            success: function (response) {
                debug.log("getInterviewById", response.data);
            },
            error: function (xhr) {
                debug.error(xhr.responseJSON.message)
            }
        });
    }

    let currentStatus
    $(document).on("click", ".nav-item", function () {
        $(".nav-item button").removeClass("active text-primary").addClass("text-black");
        $(this).find("button").removeClass("text-black").addClass("active text-primary");
        let status = $(this).data("status")
        $(".shortlist-content").html(`  <div class="border-0 p-3 d-flex flex-column align-items-center">
                                            <i class="fa-regular fa-folder-open" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
                                            <h4 class="mt-2">No Interview Selected Yet</h4>
                                            <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
                                                <i class="fa-solid fa-filter"></i>
                                                Interview
                                            </button>
                                        </div>`)
        if (currentStatus == status) return
        currentStatus = status
        getInterviewsByStatus(currentStatus)
    });

    $(document).on("click", "#btnMessageStudent", function () { })

    $(document).on("click", "#btnReschedule", function () {
        let id = $(this).data("id")

    })

    $(document).on("click", "#btnCencelInterview", function () {
        let id = $(this).data("id")
        cancelInterview(id)

    })

    $(document).on("click", "#btnCompletedInterview", function () {
        let id = $(this).data("id")
        completeInterview(id)
    })

    // If belum interview, nak update interview kene masukkan interview_result
    // Apa func INTERVIEW_STATUS => Rescheduled

    // getInterviewsBySenderId()
    // completeInterview(3)
    // cancelInterview(5)
    // update(3)
    // store(1)
    // getInterviewsByStatus("Completed")
    // ggetInterviewById(2)

    async function getPositionsByOrgId(id) {
        let url = "{{ route('positions.getPositionsByOrgId', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", id);

        return await $.ajax({
            url: url,
            method: 'GET'
        });
    } async function getProfileDataByProfileId() {
        let url = "{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", "{{ session('user_profile_id') }}");
        let response = await $.ajax({
            url: url,
            method: 'GET'
        });

        return response.data;
    }

    async function loadData() {
        try {
            let profile = await getProfileDataByProfileId();
            let organizations = profile.organization_users;
            console.log(organizations);


            let results = await Promise.all(
                organizations.map(organization => getPositionsByOrgId(organization.organization_id))
            );

            results.forEach(pos => console.group(pos))


        } catch (error) {
            console.error("Ralat semasa loadData:", error);
        }
    }
    loadData()

    function getPositionById(id) {
        let url = "{{ route('positions.getPositionById', ['id' => '__ID__']) }}"
        url = url.replace("__ID__", id)
        $.ajax({
            url,
            type: "GET",
            success: function (response) {
                debug.log("getPositionById", response.data)
            },
            error: function (xhr) {
                debug.error(xhr.responseJSON.message)
            }
        });
    }
    getPositionById(1)
</script>
@endsection
