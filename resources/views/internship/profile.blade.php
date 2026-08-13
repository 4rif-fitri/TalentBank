@extends('layouts.internship-layouts')

@section('content')
    <div class="user-card">
        <div class="user-card-left">
            <section class="user-card-header">

                <div class="user-card-banner" style="background-image: url('../assets/internship-assets/images/7.jpg');">
                    <div class="icon-container">
                        <label for="coverPhotoInput" class="btn icon bg-body">
                            <i class="fa-solid fa-pencil"></i>
                            <input type="file" hidden id="coverPhotoInput" accept="image/*">
                        </label>
                    </div>
                </div>

                <div class="user-profile-group">
                    <div class="profile-image" style="background-image: url('../assets/internship-assets/images/7.jpg');">
                        <div class="w-100 h-100 position-relative">
                            <label for="profileImageInput" class="btn icon bg-body">
                                <i class="fa-solid fa-camera"></i>
                                <input type="file" hidden id="profileImageInput" accept="image/*">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="user-profile-detail">
                    <div class="icon-container" style="top:-5px;">
                        <button type="button" class="btn btn-secondary icon" id="btnEditProfile">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                    </div>
                    <h2 id="name" class="fw-bold">Lorem bin Ipsum</h2>
                    <p id="headline">Computer Science Student | Web Developer | UI/UX Enthusiast</p>
                    <a id="uni-name" href="#" class="h5 fw-bold">Universiti Teknikal Malaysia Melaka (UTeM)</a>
                    <p id="programme">Bachelor of Computer Science (Software Engineering)</p>

                    <article id="location">
                        <p>
                            <i class="fa-solid fa-location-dot"></i>
                            <span id="profileLocation">Durian Tunggal, Melaka, Malaysia</span>|
                            <i class="fa-solid fa-user-check verified"></i>
                            University Verified
                        </p>
                    </article>

                    <article class="links">
                        <a class="badge text-bg-light d-flex align-items-center gap-1"
                            href="https://instagram.com/ariffitri" target="_blank" rel="noopener noreferrer">
                            <i class="fa-brands fa-instagram"></i>
                            Instagram
                        </a>

                        <a class="badge text-bg-light d-flex align-items-center gap-1" href="https://tiktok.com/@ariffitri"
                            target="_blank" rel="noopener noreferrer">
                            <i class="fa-brands fa-tiktok"></i>
                            Tiktok
                        </a>

                        <a class="badge text-bg-light d-flex align-items-center gap-1" href="https://github.com/4rif-fitri"
                            target="_blank" rel="noopener noreferrer">
                            <i class="fa-brands fa-github"></i>
                            Github
                        </a>

                        <a class="badge text-bg-light d-flex align-items-center gap-1" href="https://x.com/ariffitri"
                            target="_blank" rel="noopener noreferrer">
                            <i class="fa-brands fa-x-twitter"></i>
                            Twitter
                        </a>

                        <a href="./links.html" class="btn badge text-bg-primary">
                            <i class="fa-solid fa-pencil"></i>
                            Edit
                        </a>
                    </article>


                    <div class="btn-container">

                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom">
                            Add Section
                        </button>

                    </div>

                </div>

                <nav class="horizontal-nav d-flex gap-4 px-3">
                    <button type="button" class="profile-tab active d-flex justify-content-center" data-target="main">
                        Main
                    </button>
                    <button type="button" class="profile-tab d-flex justify-content-center" data-target="result">
                        Result Sem
                    </button>
                </nav>

            </section>

            <div id="mainTabContent">

                <section id="about">
                    <h3 class="fw-bold text-sm-center text-lg-start">About</h3>
                    <hr>
                    <p id="aboutText">
                        Motivated computer science student with experience
                        in web development, database design and user interface
                        prototyping. Interested in building responsive and
                        user-friendly applications using Laravel, JavaScript
                        and modern frontend tools.
                    </p>
                    <div class="icon-container">
                        <button type="button" class="icon btn btn-secondary" id="btnEditAbout">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                    </div>
                </section>

                <section id="education" class="d-flex flex-column gap-1">
                    <h3 class="fw-bold text-sm-center text-lg-start">Education</h3>
                    <div class="icon-container">
                        <a href="{{ route('profile.education') }}" class="btn btn-secondary icon">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                    </div>
                    <hr>

                    <article class="gap-2">
                        <div class="d-flex gap-1 align-items-center">
                            <img src="{{ asset('../assets/internship-assets/images/7.jpg') }}" alt="UTeM"
                                style="width:50px;height:50px;border-radius:50%;object-fit:cover;">
                            <p class="h5 fw-bold">
                                Universiti Teknikal Malaysia Melaka
                                (UTeM)
                            </p>
                        </div>


                        <div class="col d-flex flex-column mx-1 gap-1">
                            <p>Bachelor of Computer Science (Software Engineering)</p>
                            <p>Oct 2026 - Oct 2027</p>
                            <p>Grade: 4.00</p>
                            <p>Currently pursuing a degree in software engineering with emphasis on system</p>

                            <div class="skills d-flex flex-wrap align-items-center">
                                Skills:
                                <div class="badge text-bg-secondary m-1">Software Engineering</div>
                                <div class="badge text-bg-secondary m-1">System Analysis</div>
                                <div class="badge text-bg-secondary m-1">Database Design</div>
                                <div class="badge text-bg-secondary m-1">Web Development</div>
                            </div>

                            <div class="images d-flex flex-wrap">
                                <div class="image rounded-1 m-1"
                                    style="background-image:url('../../assets/internship-assets/images/7.jpg'); cursor:pointer;"
                                    data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-slide-index="0">
                                </div>
                                <div class="image rounded-1 m-1"
                                    style=" background-image:url('../assets/internship-assets/images/7.jpg');cursor:pointer;"
                                    data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-slide-index="1">
                                </div>
                                <div class="image rounded-1 m-1 d-flex justify-content-center align-items-center"
                                    style="background-image:url('../assets/internship-assets/images/7.jpg'); filter:brightness(.5);cursor:pointer;"
                                    data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-slide-index="2">
                                    <h4 class="text-white m-0">
                                        +5
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </article>
                    <hr>

                    <a href="./education.html" class="d-flex justify-content-center align-items-center">
                        <span>Show All</span>
                    </a>
                </section>

                <section id="projects" class="d-flex flex-column gap-1">
                    <h3 class="fw-bold text-sm-center text-lg-start">Project</h3>
                    <div class="icon-container">
                        <a href="./project.html" class="btn btn-secondary icon">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                    </div>
                    <hr>

                    <article class="m-1 d-flex flex-column gap-2">
                        <p class="h5 fw-bold">EduMaths – Complements to 10 Learning Platform</p>
                        <p>Jul 2026 - Aug 2026</p>
                        <p>Designed an interactive mathematics learning platform for primary school pupils.</p>
                        <div class="skills d-flex flex-wrap align-items-center">
                            Skills:
                            <div class="badge text-bg-secondary m-1">Software Engineering</div>
                            <div class="badge text-bg-secondary m-1">System Analysis</div>
                            <div class="badge text-bg-secondary m-1">Database Design</div>
                            <div class="badge text-bg-secondary m-1">Web Development</div>
                        </div>

                        <div class="images d-flex flex-wrap">
                            <div class="image rounded-1 m-1"
                                style="background-image:url('../assets/internship-assets/images/7.jpg');">
                            </div>
                            <div class="image rounded-1 m-1"
                                style="background-image:url('../assets/internship-assets/images/7.jpg');">
                            </div>
                            <div class="image rounded-1 m-1 d-flex justify-content-center align-items-center"
                                style="background-image:url('../assets/internship-assets/images/7.jpg'); filter:brightness(.5);">
                                <h4 class="text-white m-0">+5</h4>
                            </div>
                        </div>
                    </article>
                    <hr>

                    <a href="./project.html" class="d-flex justify-content-center align-items-center">Show All</a>
                </section>

                <section id="experience" class="d-flex flex-column gap-1">
                    <h3 class="fw-bold text-sm-center text-lg-start">Experience</h3>
                    <div class="icon-container">
                        <a href="{{ route('profile.experience') }}" class="btn btn-secondary icon">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                    </div>
                    <hr>

                    <article class="m-1">
                        <div class="d-flex gap-1 align-items-center">
                            <img src="{{ asset('../assets/internship-assets/images/7.jpg') }}" alt="UTeM"
                                style="width:50px; height:50px; border-radius:50%; object-fit:cover;">
                            <p class="h5 fw-bold">Web Developer Intern</p>
                        </div>

                        <div class="col d-flex flex-column mx-1">
                            <p>Wahdah Technology - Internship</p>
                            <p>Jul 2026 - Present</p>
                            <p>Durian Tunggal, Melaka, Malaysia</p>
                            <p>Contributed to Laravel-based web applications</p>

                            <div class="skills d-flex flex-wrap align-items-center">
                                Skills:
                                <div class="badge text-bg-secondary m-1">Software Engineering</div>
                                <div class="badge text-bg-secondary m-1">System Analysis</div>
                                <div class="badge text-bg-secondary m-1">Database Design</div>
                                <div class="badge text-bg-secondary m-1">Web Development</div>
                            </div>

                            <div class="images d-flex flex-wrap">
                                <div class="image rounded-1 m-1"
                                    style="background-image:url('../assets/internship-assets/images/7.jpg');"></div>
                                <div class="image rounded-1 m-1"
                                    style="background-image:url('../assets/internship-assets/images/7.jpg');"></div>
                                <div class="image rounded-1 m-1 d-flex justify-content-center align-items-center"
                                    style="background-image:url('../assets/internship-assets/images/7.jpg'); filter:brightness(.5);">
                                    <h4 class="text-white m-0">+5</h4>
                                </div>
                            </div>

                        </div>

                    </article>

                    <hr>

                    <a href="./experience.html" class="d-flex justify-content-center align-items-center">Show All</a>
                </section>

                <section id="honorsAwards" class="d-flex flex-column gap-1">
                    <h3 class="fw-bold text-sm-center text-lg-start">Honors & Awards</h3>

                    <div class="icon-container">
                        <a href="./honors&awards.html" class="btn btn-secondary icon">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                    </div>
                    <hr>

                    <article class="m-1 d-flex flex-column gap-2">
                        <p class="h5 fw-bold">Dean's List Award</p>
                        <p>Associated with Universiti Teknikal Malaysia Melaka</p>
                        <p>Issued Oct 2025</p>
                        <p>Recognised for strong academic performance in the Diploma in Computer Science</p>

                        <div class="skills d-flex flex-wrap align-items-center">
                            Skills:
                            <div class="badge text-bg-secondary m-1">Software Engineering</div>
                            <div class="badge text-bg-secondary m-1">System Analysis</div>
                            <div class="badge text-bg-secondary m-1">Database Design</div>
                            <div class="badge text-bg-secondary m-1">Web Development</div>
                        </div>

                        <div class="images d-flex flex-wrap">
                            <div class="image rounded-1 m-1"
                                style="background-image:url('../assets/internship-assets/images/7.jpg');">
                            </div>
                        </div>
                    </article>
                    <hr>

                    <a href="./honors&awards.html" class="d-flex justify-content-center align-items-center">Show All</a>
                </section>

                <section id="certifications" class="d-flex flex-column gap-1">
                    <h3 class="fw-bold text-sm-center text-lg-start">Certifications</h3>
                    <div class="icon-container">
                        <a href="./certifications.html" class="btn btn-secondary icon">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                    </div>
                    <hr>

                    <article class="m-1">
                        <p class="h5 fw-bold">Oracle Certified Foundations Associate, Database</p>
                        <p>Oracle Academy</p>
                        <p>Issued Oct 2025</p>
                        <p>Credential ID: OCFA-ARIF-2025</p>

                        <a class="btn btn-outline-secondary d-flex justify-content-center align-items-center gap-2"
                            style="width:200px;" target="_blank" rel="noopener noreferrer" href="#">
                            <span>Show credential</span>
                            <i class="fa-solid fa-up-right-from-square"></i>
                        </a>

                        <div class="skills d-flex flex-wrap align-items-center">
                            Skills:
                            <div class="badge text-bg-secondary m-1">Software Engineering</div>
                            <div class="badge text-bg-secondary m-1">System Analysis</div>
                            <div class="badge text-bg-secondary m-1">Database Design</div>
                            <div class="badge text-bg-secondary m-1">Web Development</div>
                        </div>
                    </article>
                    <hr>

                    <a href="./certifications.html" class="d-flex justify-content-center align-items-center">Show All</a>
                </section>

            </div>

            <div id="resultTabContent" class="d-none">
                <section>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold">Semester Results</h3>
                        <button class="btn btn-primary" id="addResult">
                            <i class="fa-solid fa-plus"></i>
                            Add Result
                        </button>
                    </div>
                    <hr>

                    <div id="semesterResultList">

                    </div>

                </section>
            </div>

        </div>

        <div class="user-card-right">
            <!-- LANGUAGES -->
            <section id="languages" class="d-flex flex-column gap-1">
                <h3 class="fw-bold text-sm-center text-lg-start">Languages</h3>
                <div class="icon-container">
                    <a href="./languages.html" class="btn btn-secondary icon">
                        <i class="fa-solid fa-pencil"></i>
                    </a>
                </div>
                <hr>

                <article class="m-1">
                    <p class="fw-bold">Bahasa Melayu</p>
                    <p>Native</p>
                </article>

                <article class="m-1">
                    <p class="fw-bold">English</p>
                    <p> Professional working proficiency</p>
                </article>
                <hr>

                <a href="./languages.html" class="d-flex justify-content-center align-items-center">Show All</a>
            </section>

            <!-- SKILLS -->
            <section id="skills" class="d-flex flex-column gap-1">
                <h3 class="fw-bold text-sm-center text-lg-start">Skills</h3>
                <div class="icon-container">
                    <a href="./skills.html" class="btn btn-secondary icon">
                        <i class="fa-solid fa-pencil"></i>
                    </a>
                </div>
                <hr>

                <article class="m-1">
                    <p class="fw-bold">Database</p>
                    <p>Advanced</p>
                </article>

                <article class="m-1">
                    <p class="fw-bold">HTML</p>
                    <p>Advanced</p>
                </article>
                <hr>

                <a href="./skills.html" class="d-flex justify-content-center align-items-center">Show All</a>
            </section>

        </div>

    </div>

    <x-offcanvas />

    <x-modals.imagePreviewModal />

    <x-modals.editAboutModal />

    <x-modals.editProfileModal />

    <x-modals.addResultModal />
@endsection

@section('script')
    @if (session('status') == 200)
        <script>
            Swal.fire({
                title: "Success",
                text: @json(session('message')),
                icon: "success"
            });
        </script>
    @endif
    @if ($errors->any())
        <script>
            Swal.fire({
                title: "Upload Failed",
                text: @json($errors->first()),
                icon: "error"
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {

            $(".profile-tab").on("click", function() {
                $(".profile-tab").removeClass("active");
                $(this).addClass("active");

                const target = $(this).data("target");
                if (target === "main") {
                    $("#mainTabContent").removeClass("d-none");
                    $("#resultTabContent").addClass("d-none");

                } else if (target === "result") {
                    $("#mainTabContent").addClass("d-none");
                    $("#resultTabContent").removeClass("d-none");
                    if (!resultLoaded) loadSemesterResults()
                }
            });
        });
    </script>
@endsection
