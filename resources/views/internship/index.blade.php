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
    {{-- <div class="errorMessage"></div>

    <div class="p-4 d-flex flex-column align-items-center">
        @if (request()->is("*/saves*"))
            @include('social-media.components.search-bar', ['searchUrl' => route('social-media.saves')])
        @else
            @include('social-media.components.search-bar', ['searchUrl' => route('social-media.index')])
        @endif

        <div id="posts">
            @include('social-media.components.post-list', ['posts' => $posts])
        </div>

        <div id="post-loading">Loading...</div>

        @include('social-media.components.comment-modal')

        @include('social-media.components.share-post-modal')
    </div> --}}
@endsection

@section('script')
    <script>

    </script>
@endsection
