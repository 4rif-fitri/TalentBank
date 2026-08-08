@extends('layouts.internship-layouts')

@section('css')
    <link href="{{ URL::asset('assets/libs/chartist/chartist.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .errorMessage {
            color: red;
        }

        #posts {
            width: 100%;
            max-width: 900px;
        }
    </style>
@endsection

@section('content')
    <div class="errorMessage"></div>

    <div class="p-4 d-flex flex-column align-items-center">
        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Odio, ut necessitatibus. Dolor maiores ducimus modi. Cumque, possimus perferendis laboriosam similique amet ratione commodi veritatis tenetur nemo et deleniti ipsa atque!
    </div>
@endsection

@section('script')
    <script>

    </script>
@endsection
