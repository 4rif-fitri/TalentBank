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

            <div class="user-card-left">
                <div class="user-card-header">
                    <div class="user-card-banner">
                        <div class="icon-container">
                            <div class="waves-effect icon">
                                <i class="ti-pencil ti-md"></i>
                            </div>
                        </div>
                    </div>

                    <div class="user-profile-group">
                        <div class="profile-image">
                            <div class="icon-container">
                                <div class="icon">
                                    <i class="ti-camera ti-md"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="user-profile-ditail">
                        <div class="icon-container">
                            <div class="waves-effect icon">
                                <i class="ti-pencil ti-md"></i>
                            </div>
                        </div>

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

                <section id="about">
                    <h2>About</h2>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ad, ex porro facilis
                        accusamus eos consectetur, repellendus fugiat officiis, illum nesciunt
                        voluptatem
                        sint possimus nihil obcaecati. Laudantium praesentium consequatur adipisci
                        eligendi?
                    </p>

                    <div class="icon-container">
                        <div class="waves-effect icon">
                            <i class="ti-pencil ti-md"></i>
                        </div>
                    </div>
                </section>

                <section id="education" class="d-flex flex-column gap-1">
                    <h2>Education (2)</h2>

                    <div class="icon-container">
                        <div class="icon">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div class="icon">
                            <i class="ti-pencil ti-md"></i>
                        </div>
                    </div>
                    <hr>

                    <article class="m-1">
                        <div>
                            <img src="./utemjpg.jpg" alt="" style="width: 50px !important; border-radius: 50%;">
                        </div>
                        <div class="col d-flex flex-column mx-1">
                            <h4>Universiti Teknilal Malaysia Melaka (UTeM)</h4>
                            <p>Degree of Computer Applications</p>
                            <p>Jan 2020 - Dec 2026</p>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse
                                mollitiasapiente labore deleniti, earum fugit facilis molestias minus
                                autem ex ipsamnon repellendus dolorem, vero maxime iusto distinctio
                                alias natus.</p>

                            <div class="skills d-flex flex-wrap align-items-center">
                                Skills:
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>Laravel</span>
                                </a>
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>Laravel</span>
                                </a>
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>Laravel</span>
                                </a>
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>Laravel</span>
                                </a>
                            </div>

                            <div class="images d-flex flex-wrap">
                                <div class="image rounded-5 mx-1"
                                    style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}');">
                                </div>
                                <div class="image rounded-5 m-1 d-flex justify-content-center align-items-center"
                                    style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}'); filter: brightness(0.5);">
                                    <h4 class="text-white">+5</h4>
                                </div>
                            </div>
                        </div>

                        <hr>

                    </article>



                    <hr>

                    <a href="" class="d-flex justify-content-center align-items-center">
                        <h4>Show All</h4>
                    </a>

                </section>

                <section id="projects" class="d-flex flex-column gap-1">
                    <h2>Project (2)</h2>

                    <div class="icon-container">
                        <div class="icon">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div class="icon">
                            <i class="ti-pencil ti-md"></i>
                        </div>
                    </div>

                    <hr>

                    <article class="m-1 d-flex flex-column gap-2">
                        <h4>Game Math</h4>
                        <p>Mar 2025 - Aug 2026</p>

                        <div class="d-flex align-items-center company-group">
                            <img src="./utemjpg.jpg" alt="" style="width: 50px; border-radius: 50%;">
                            <p>Associated with Universiti Teknikal Malaysia Melaka</p>
                        </div>

                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse mollitia
                            sapiente
                            labore deleniti, earum fugit facilis molestias minus autem ex ipsam non
                            repellendus dolorem, vero maxime iusto distinctio alias natus.
                        </p>

                        <div class="skills">
                            <a href="" class="waves-effect bg-secondary">
                                <i class="fa-brands fa-laravel"></i>
                                <span>Laravel</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary">
                                <i class="fa-brands fa-vuejs"></i>
                                <span>Vue</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary">
                                <i class="fa-brands fa-react"></i>
                                <span>React</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary">
                                <i class="fa-brands fa-html5"></i>
                                <span>Html</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary">
                                <i class="fa-brands fa-css3"></i>
                                <span>Css</span>
                            </a>
                        </div>

                        <div class="images d-flex flex-wrap">
                            <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');"></div>
                            <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');"></div>
                            <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');"></div>
                            <div class="image rounded-5 m-1 d-flex justify-content-center align-items-center"
                                style="background-image: url('./cover-image.png'); filter: brightness(0.5);">
                                <h4 class="text-white">+5</h4>
                            </div>
                        </div>

                        <hr>
                    </article>

                    <article class="m-1 d-flex flex-column gap-2">
                        <h4>Game Math</h4>
                        <p>Mar 2025 - Aug 2026</p>

                        <div class="d-flex align-items-center company-group">
                            <img src="./utemjpg.jpg" alt="" style="width: 50px; border-radius: 50%;">
                            <p>Associated with Universiti Teknikal Malaysia Melaka</p>
                        </div>

                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse mollitia
                            sapiente
                            labore deleniti, earum fugit facilis molestias minus autem ex ipsam non
                            repellendus dolorem, vero maxime iusto distinctio alias natus.
                        </p>

                        <div class="skills">
                            <a href="" class="waves-effect bg-secondary">
                                <i class="fa-brands fa-laravel"></i>
                                <span>Laravel</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary">
                                <i class="fa-brands fa-vuejs"></i>
                                <span>Vue</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary">
                                <i class="fa-brands fa-react"></i>
                                <span>React</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary">
                                <i class="fa-brands fa-html5"></i>
                                <span>Html</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary">
                                <i class="fa-brands fa-css3"></i>
                                <span>Css</span>
                            </a>
                        </div>

                        <div class="images d-flex flex-wrap">
                            <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');"></div>
                            <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');"></div>
                            <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');"></div>
                            <div class="image rounded-5 m-1 d-flex justify-content-center align-items-center"
                                style="background-image: url('./cover-image.png'); filter: brightness(0.5);">
                                <h4 class="text-white">+5</h4>
                            </div>
                        </div>

                        <hr>
                    </article>

                    <hr>

                    <a href="" class="d-flex justify-content-center align-items-center">
                        <h4>Show All</h4>
                    </a>

                </section>

                <section id="experience" class="d-flex flex-column gap-1">
                    <h2>Experience</h2>

                    <div class="icon-container">
                        <div class="icon">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div class="icon">
                            <i class="ti-pencil ti-md"></i>
                        </div>
                    </div>

                    <hr>

                    <article class="m-1">
                        <div>
                            <img src="./utemjpg.jpg" alt="" style="width: 50px !important; border-radius: 50%;">
                        </div>
                        <div class="col d-flex flex-column mx-1">
                            <h4>Web Developer</h4>
                            <a href="">Infineon Technologies - Internship</a>
                            <p>Jan 2020 - Dec 2026</p>
                            <p>Melaka, Malaysia - On-site</p>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse alias
                                natus.
                            </p>
                            <div class="skills d-flex flex-wrap align-items-center">
                                Skills:
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>Laravel</span>
                                </a>
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>Laravel</span>
                                </a>
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>Laravel</span>
                                </a>
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>Laravel</span>
                                </a>
                            </div>

                            <div class="images d-flex flex-wrap">
                                <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');">
                                </div>
                                <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');">
                                </div>
                                <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');">
                                </div>
                                <div class="image rounded-5 m-1 d-flex justify-content-center align-items-center"
                                    style="background-image: url('./cover-image.png'); filter: brightness(0.5);">
                                    <h4 class="text-white">+5</h4>
                                </div>
                            </div>
                        </div>

                        <hr>

                    </article>



                    <hr>

                    <a href="" class="d-flex justify-content-center align-items-center">
                        <h4>Show All</h4>
                    </a>

                </section>

                <section id="Honors_awards" class="d-flex flex-column gap-1">
                    <h2>Honors & awards (2)</h2>

                    <div class="icon-container">
                        <div class="icon">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div class="icon">
                            <i class="ti-pencil ti-md"></i>
                        </div>
                    </div>

                    <hr>

                    <article class="m-1 d-flex flex-column gap-2">
                        <h4>Oracle Certified Foundations Associate, Database</h4>
                        <p>Issued by FTMK UTeM · Feb 2025</p>

                        <div class="d-flex align-items-center company-group">
                            <img src="./utemjpg.jpg" alt="" style="width: 50px; border-radius: 50%;">
                            <p>Associated with Universiti Teknikal Malaysia Melaka</p>
                        </div>

                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse mollitia
                            sapiente
                            labore deleniti, earum fugit facilis molestias minus autem ex ipsam non
                            repellendus dolorem, vero maxime iusto distinctio alias natus.
                        </p>

                        <div class="skills d-flex flex-wrap align-items-center">
                            Skills:
                            <a href="" class="waves-effect bg-secondary m-1">
                                <span>Laravel</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary m-1">
                                <span>Laravel</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary m-1">
                                <span>Laravel</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary m-1">
                                <span>Laravel</span>
                            </a>
                        </div>

                        <div class="images d-flex flex-wrap">
                            <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');"></div>
                            <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');"></div>
                            <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');"></div>
                            <div class="image rounded-5 m-1 d-flex justify-content-center align-items-center"
                                style="background-image: url('./cover-image.png'); filter: brightness(0.5);">
                                <h4 class="text-white">+5</h4>
                            </div>
                        </div>

                        <hr>
                    </article>

                    <hr>

                    <a href="" class="d-flex justify-content-center align-items-center">
                        <h4>Show All</h4>
                    </a>

                </section>

                <section id="certifications" class="d-flex flex-column gap-1">
                    <h2>Certifications (2)</h2>

                    <div class="icon-container">
                        <div class="icon">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div class="icon">
                            <i class="ti-pencil ti-md"></i>
                        </div>
                    </div>

                    <hr>

                    <article class="m-1">
                        <div>
                            <img src="./utemjpg.jpg" alt="" style="width: 50px !important; border-radius: 50%;">
                        </div>
                        <div class="col d-flex flex-column mx-1">
                            <h4>Oracle Certified Foundations Associate, Database</h4>
                            <p>Oracle</p>
                            <p>Issued Oct 2025</p>
                            <a class="btn d-flex justify-content-center align-items-center"
                                style="border: 1px solid gray; border-radius: 14px; color: gray; width: 150px;"
                                href="">
                                <p>Show credential</p>
                                <i class="fa-solid fa-up-right-from-square mx-1"></i>
                            </a>

                            <div class="skills d-flex flex-wrap align-items-center">
                                Skills:
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>Laravel</span>
                                </a>
                            </div>

                            <div class="images d-flex flex-wrap">
                                <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');">
                                </div>
                            </div>
                        </div>

                        <hr>

                    </article>



                    <hr>

                    <a href="" class="d-flex justify-content-center align-items-center">
                        <h4>Show All</h4>
                    </a>

                </section>

            </div>

            <div class="user-card-right">

                <section id="skills" class="d-flex flex-column gap-1">
                    <h2>Skills (2)</h2>
                    <div class="icon-container">
                        <div class="icon">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div class="icon">
                            <i class="ti-pencil ti-md"></i>
                        </div>
                    </div>

                    <hr>

                    <article class="m-1">
                        <div class="col d-flex justify-content-between m-1">
                            <h4>
                                <i class="fa-solid fa-database"></i>
                                Database
                            </h4>
                            <p>Advance</p>
                        </div>
                    </article>

                    <article class="m-1">
                        <div class="col d-flex justify-content-between m-1">
                            <h4>
                                <i class="fa-brands fa-html5"></i>
                                HTML
                            </h4>
                            <p>Advance</p>
                        </div>
                    </article>
                    <!-- <hr> -->
                    <!-- <a href="" class="d-flex justify-content-center align-items-center">
                                        <h4>Show All</h4>
                                       </a> -->

                </section>

                <section id="languages" class="d-flex flex-column gap-1">
                    <h2>Languages (2)</h2>

                    <div class="icon-container">
                        <div class="icon">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div class="icon">
                            <i class="ti-pencil ti-md"></i>
                        </div>
                    </div>

                    <hr>

                    <article class="m-1">
                        <div class="col d-flex m-1 justify-content-between">
                            <h4>Malaysia</h4>
                            <p>Native</p>
                        </div>
                    </article>
                    <article class="m-1">
                        <div class="col d-flex justify-content-between m-1">
                            <h4>Malaysia</h4>
                            <p>Native</p>
                        </div>
                    </article>

                    <!-- <hr>

                                       <a href="" class="d-flex justify-content-center align-items-center">
                                        <h4>Show All</h4>
                                       </a> -->

                </section>
            </div>

        </div>

    </div>
@endsection

@section('script')
    <script></script>
@endsection
