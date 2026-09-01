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
        width: 350px;
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

    @media (max-width: 1300px) {
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

        <div class="shortlist-content flex-grow-1">

            <div class="bg-white row g-3" id="shortlistContent">

                <div class="d-flex">
                    <div class="icon-img"
                        style="background-image: url('http://127.0.0.1:8000/uploads/profile-image-url/default.png');">
                    </div>
                    <div>
                        <p class="fw-semibold">Name person 1</p>
                        <p>Universiti Teknikal Malaysia Melaka(Utem)</p>
                        <p>Game Technology</p>
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
                            <p>
                                ${interview.invitation.position.position_title}
                            </p>
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
                $("#shortlistList").append(template(interview))
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