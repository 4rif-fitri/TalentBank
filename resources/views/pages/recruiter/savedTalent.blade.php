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
        $.ajax({
            url: "url",
            type: "GET",
            data: "data",
            dataType: "dataType",
            success: function (response) {
                console.log(response);
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
    </script>
    @endsection