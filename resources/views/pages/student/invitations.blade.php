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
        <h3 class="m-0 fw-bold">Recruitment Invitation</h3>
    </div>

    <div class="talent-layout">

        <aside class="results-panel" id="filterPanel">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <button class="nav-link active text-primary">All</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-body">Pending</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-body">Accepted</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-body">Dealined</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-body">Exprired</button>
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
<script>
    function toggleFilter() {
        document.body.classList.toggle('filter-open');
    }
</script>
<script>
    $.ajax({
        url: "{{ route('invitations.getInvitationsByReceiverId') }}",
        type: "GET",
        success: function (response) {
            console.log("getInvitationsByReceiverId", response)
            $(".invitation-list").empty()
            let invitations = response.data

            invitations.forEach(inv => $(".invitation-list").append(invitation.reciverInvitationList(inv)));
        },
        error: function (xhr) {
            console.error(xhr)

        }
    });

    function getInvitationById(id) {
        url = "{{ route('invitations.getInvitationById', ['id' => '__ID__' ]) }}"
        url = url.replace("__ID__", id)

        return $.ajax({
            url,
            type: "GET"
        });
    }

    async function handleSelectedInvitation() {
        let id = $(this).data('id');

        try {
            let invitationDetail = await getInvitationById(id);
            console.log(invitationDetail);

            $("#shortlistContent").empty()
            $("#shortlistContent").append(invitation.reciverInvitatinMainContent(invitationDetail.data))

        } catch (error) {
            console.error(error);

        }
    }


    $(document).on("click", ".invitation-item", handleSelectedInvitation)


    // // Once update only
    // url = "{{ route('invitations.acceptInvitation', ['id' => '__ID__' ]) }}"
    // url = url.replace("__ID__", 6)
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
    //         console.log("acceptInvitation", response)
    //     },
    //     error: function (xhr) {
    //         console.error(xhr)

    //     }
    // });

    // // Once update only
    // url = "{{ route('invitations.rejectInvitation', ['id' => '__ID__' ]) }}"
    // url = url.replace("__ID__", 7)
    // formData = new FormData()
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
    //         console.log("rejectInvitation", response)
    //     },
    //     error: function (xhr) {
    //         console.error(xhr)

    //     }
    // });

</script>
@endsection