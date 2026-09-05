@extends('layouts.internship-layouts')

@section('css')
@endsection

@section('content')
<div class="content p-4">

    <div class="d-flex justify-content-between mb-2 align-items-center flex-lg-row gap-3">
        <h3 class="m-0 fw-bold">Saved Talent</h3>
    </div>

    <div class="shortlist-layout">

        <div class="shortlist-content bg-body flex-grow-1 p-2 rounded card">
            <div class="row g-3" id="shortlistContent">

            </div>
        </div>
    </div>
    @endsection

@section('script')
<script>

    function getShortlistedPositionIds(profileId, orgId){
        let url = "{{ route('shortlists.getShortlistedPositionIds', ['profileId' => '__profileId__', 'orgId' => '__orgId__']) }}"
        url = url.replace("__profileId__", profileId)
        url = url.replace("__orgId__", orgId)
        $.ajax({
            url,
            type: "GET",
            success: function (response) {
                console.log(response);
            },
            error: function (xhr) {
                console.error(xhr);
            }
        });
    }

    function store(position_id, user_profile_id){

        let data = {
            user_profile_id: user_profile_id,
            position_id: position_id,
            _token: $('meta[name="csrf-token"]').attr("content")
        }

        $.ajax({
            url: "{{ route('shortlists.store') }}",
            type: "POST",
            data,
            success: function (response) {
                console.log(response);
            },
            error: function (xhr) {
                console.error(xhr);
            }
        });
    }
    // store(11,30)

    getShortlistedPositionIds(30,6)
</script>
@endsection
