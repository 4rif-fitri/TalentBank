@extends('layouts.internship-layouts')

@section('css')
    <link href="{{ URL::asset('assets/libs/chartist/chartist.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/css/profile/style.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .errorMessage {
            color: red;
        }

        #posts {
            width: 100%;
            max-width: 900px;
        }

        .user-card-header .icon {
            position: absolute;
            right: 10px;
            top: 10px;
        }

        .profile-image .icon {
            position: absolute;
            bottom: 10px;
            right: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="errorMessage"></div>

    <div class="p-4 d-flex flex-column align-items-center">

        <div class="user-card">

            <div class="user-card-header">
                <div class="user-card-banner">
                    <div class="waves-effect icon">
                        <i class="ti-pencil ti-md"></i>
                    </div>
                </div>

                <div class="user-profile-group">
                    <div class="profile-image">
                        <div class="waves-effect icon">
                            <i class="ti-camera ti-md"></i>
                        </div>
                    </div>
                </div>

                <div class="user-profile-ditail">
                    <h2 id="name">Lorem Lorem Bin Lorem Lorem</h2>
                    <p id="headline">Final Year Telecommunication Technology Student</p>
                    <a href="" id="uni-name">
                        <h5>Universiti Teknilal Malaysia Melaka (UTeM)</h5>
                    </a>
                    <p id="programe">IJAZAH SARJANA MUDA KEJURUTERAAN ELEKTRIK DENGAN KEPUJIAN</p>

                    <article id="location">
                        <p>
                            <i class="ti-location-pin ti-md"></i>
                            Melaka, Malaysia |
                            <i class="fa-solid fa-user-check verified"></i>
                            University Verified
                        </p>
                    </article>

                    <article class="links">
                        <a href="">
                            <i class="fa-brands fa-youtube"></i>
                            Youtube
                        </a>
                        <a href="">
                            <i class="fa-brands fa-facebook"></i>
                            FaceBook
                        </a>
                        <a href="">
                            <i class="fa-brands fa-instagram"></i>
                            Instagram
                        </a>
                        <a href="">
                            <i class="fa-brands fa-tiktok"></i>
                            Tiktok
                        </a>
                        <a href="">
                            <i class="fa-brands fa-linkedin"></i>
                            Linkedin
                        </a>
                        <a href="">
                            <i class="fa-brands fa-github"></i>
                            Github
                        </a>
                        <a href="">
                            <i class="fa-brands fa-x-twitter"></i>
                            Twitter
                        </a>
                    </article>

                    <div class="btn-container">
                        <button class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i>
                            Add Section
                        </button>
                        <button class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i>
                            Copy Link
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('script')
    <script></script>
@endsection
