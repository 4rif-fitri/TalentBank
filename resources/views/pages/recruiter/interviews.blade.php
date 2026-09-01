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
                <h5 class="m-0 fw-bold">Upcoming Interview</h5>
            </div>

            <div id="shortlistList"></div>
        </aside>

        <div class="shortlist-content bg-body flex-grow-1 p-2 rounded card">
            <div class="row g-3 ">
                <div class="h-100 border-0 p-3 position-relative">

                    <div class="mb-3 d-flex flex-xl-row flex-column gap-3">
                        <div class="bg-primary"
                            style="width:6rem; height:6rem; border-radius: 50%; background-size: cover; background-image:url('${window.appConfig.profileImageUrl}/${inv.receiver.profile_image}')">
                        </div>

                        <div>
                            <h3 class="fw-semibold">Dr Lorem Ipsum Dolor Sit Amit</h3>
                            <p class="fw-semibold">Sofware inginer with Honor</p>
                            <small class="text-muted d-block">Universiti Teknikal Malaysia Melaka(UTEM)</small>
                            <div class="badge bg-primary">See More</div>
                            <small class="text-muted d-block">
                                <i class="fa-solid fa-location-dot" style="color: rgb(0, 0, 0);"></i>
                                Durian Tunggal, Malaka, Malaysia
                            </small>
                        </div>
                    </div>

                    <div class="mb-3 border d-block d-xl-flex justify-content-between p-2">
                        <div class="d-flex gap-2 mb-2 mb-xl-0 align-items-center">
                            <i class="fa-solid fa-briefcase bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Position</small>
                                <h5 class="fw-semibold">FrontEnd Intern</h5>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mb-2 mb-xl-0 align-items-center">
                            <i class="fa-solid fa-building bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Department</small>
                                <h5 class="fw-semibold">Department IT and DEV</h5>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mb-2 mb-xl-0 align-items-center">
                            <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">employment_type</small>
                                <h5 class="fw-semibold">employment_type</h5>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 border d-flex gap-2 p-2 flex-wrap">
                        <div class="d-flex align-items-center gap-2 mb-2 mb-xl-0">
                            <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">nterview mode</small>
                                <h5 class="fw-semibold">Online</h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2 mb-xl-0">
                            <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Meeting Link</small>
                                <h5 class="fw-semibold">http://127.0.0.1:8000/recruiter/invitations</h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2 mb-xl-0">
                            <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                            <div>
                                <small class="text-muted">Location</small>
                                <h5 class="fw-semibold">Durian Tunggal, Melaka, Malaysia</h5>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 border d-flex flex-column g-1 p-2">
                        <h4 class="mb-0 fw-semibold">Schedule</h4>
                        <div class="d-flex gap-4">
                            <div class="d-flex align-items-center gap-2 mb-2 mb-xl-0">
                                <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                    style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                                <small class="text-muted">Interview mode</small>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2 mb-xl-0">
                                <i class="fa-solid fa-calendar-check bg-secondary text-white p-2 d-flex justify-content-center align-items-center"
                                    style="width: 2rem; height: 2rem; color: rgb(0, 0, 0); font-size: 1rem; border-radius: 50%;"></i>
                                <small class="text-muted">employment_type</small>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h6 class="fw-semibold mb-1">Actions</h6>
                        <div class="d-flex gap-2 flex-row flex-sm-wrap">
                            <button
                                class="btn btn-outline-primary d-flex justify-content-center align-items-center gap-2">
                                <i class="fa-regular fa-message text-primary"></i>
                                <p>Message Student</p>
                            </button>
                            <button
                                class="btn btn-outline-primary d-flex justify-content-center align-items-center gap-2">
                                <i class="fa-regular fa-calendar-check text-primary"></i>
                                <p>Reschedule</p>
                            </button>
                            <button
                                class="btn btn-outline-danger d-flex justify-content-center align-items-center gap-2">
                                <i class="fa-solid fa-trash-can text-danger"></i>
                                <p>Cencel Interview</p>
                            </button>
                            <button
                                class="btn btn-outline-success d-flex justify-content-center align-items-center gap-2">
                                <i class="fa-solid fa-check-circle text-success"></i>
                                <p class="text-success">Mark as Completed</p>
                            </button>
                        </div>
                    </div>

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

    function template(interview) {
        return `<div data-id=${interview.id} class="d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded"
                    onclick="toggleFilter()">
                    <div role="button" class="d-flex align-items-center gap-3">
                        <div class="icon-img" style="background-image: url('{{ url('/') }}/{{ env('PROFILE_IMAGE_URL') }}/${interview.invitation.receiver.profile_image}');"></div>
                        <div class="flex-grow-1 d-flex flex-column">
                            <p class="fw-semibold">${interview.invitation.receiver.name}</p>
                            <p>${interview.invitation.position.position_title}</p>
                            <p>${interview.interview_mode}</p>
                        </div>
                    </div>

                    <i class="fa-solid fa-angle-right" style="color: rgb(0, 0, 0);"></i>
                </div>`
    }

    let url = "{{ route('interviews.getInterviewsBySenderId', ['id' => '__ID__' ]) }}"
    url = url.replace("__ID__", 1)

    $.ajax({
        url,
        type: "GET",
        success: function (response) {
            console.log("getInterviewsBySenderId", response)

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

    url = "{{ route('interviews.getInterviewById', ['id' => '__ID__' ]) }}"
    url = url.replace("__ID__", 22)

    $.ajax({
        url,
        type: "GET",
        success: function (response) {
            console.log("getInterviewById", response)
        },
        error: function (xhr) {
            console.error(xhr)

        }
    });

    // url = "{{ route('interviews.update',['id' => '__ID__' ]) }}"
    // url = url.replace("__ID__", 2)

    // let formData = new FormData()
    // formData.append("scheduled_at", "2026-9-30 05:07:17")
    // formData.append("interview_mode", "Online")
    // formData.append("location", "")
    // formData.append("meeting_url", "http://127.0.0.1:8000/recruiter/invitations")
    // formData.append("recruiter_comment", "recruiter_comment")
    // formData.append("interview_result", "Passed")
    // formData.append("_method", "PUT")

    // $.ajax({
    //     url,
    //     data: formData,
    //     type: "POST",
    //     processData: false,
    //     contentType: false,
    //     headers: {
    //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    //     },
    //     success: function (response) {
    //         console.log("interviews.store", response)
    //     },
    //     error: function (xhr) {
    //         console.error(xhr.responseJSON.message)

    //     }
    // });

    // url = "{{ route('interviews.completeInterview',['id' => '__ID__' ]) }}"
    // url = url.replace("__ID__", 20)
    // let formData = new FormData()
    // formData.append("_method", "PUT")

    // $.ajax({
    //     url,
    //     data: formData,

    //     type: "POST",
    //     processData: false,
    //     contentType: false,
    //     headers: {
    //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    //     },
    //     success: function (response) {
    //         console.log("interviews.store", response)
    //     },
    //     error: function (xhr) {
    //         console.error(xhr.responseJSON.message)

    //     }
    // });

    // url = "{{ route('interviews.cancelInterview',['id' => '__ID__' ]) }}"
    // url = url.replace("__ID__", 19)
    // let formData = new FormData()
    // formData.append("_method", "PUT")

    // $.ajax({
    //     url,
    //     data: formData,

    //     type: "POST",
    //     processData: false,
    //     contentType: false,
    //     headers: {
    //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    //     },
    //     success: function (response) {
    //         console.log("interviews.store", response)
    //     },
    //     error: function (xhr) {
    //         console.error(xhr.responseJSON.message)

    //     }
    // });


</script>
@endsection