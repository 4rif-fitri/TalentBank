@extends('layouts.internship-layouts')

@section('css')

@endsection

@section('content')

@endsection

@section('script')
<script>
    let url = "{{ route('interviews.getInterviewsByReceiverId', ['id' => '__ID__' ]) }}"
    url = url.replace("__ID__", 2)

    $.ajax({
        url,
        type: "GET",
        success: function (response) {
            console.log("getInterviewsBySenderId", response)
        },
        error: function (xhr) {
            console.error(xhr.responseJSON.message)
        }
    });
</script>
@endsection