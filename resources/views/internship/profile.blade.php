@extends('layouts.internship-layouts')

@section('css')

@endsection

@section('content')

<div class="user-card">
    <div class="user-card-left">
        <section class="user-card-header">

            <div class="user-card-banner" style="background-image: url('/assets/images/profile/cover-image.png');">
                <div class="icon-container">
                    <label for="coverPhotoInput" class="btn icon bg-body">
                        <i class="fa-solid fa-pencil"></i>
                        <input type="file" hidden id="coverPhotoInput" accept="image/*">
                    </label>
                </div>
            </div>

            <div class="user-profile-group">
                <div class="profile-image" style="background-image: url('/assets/images/profile/cover-image.png');">
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
                        <img src="{{ asset('assets/images/profile/cover-image.png') }}" alt="UTeM"
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
                                style="background-image:url('/assets/images/profile/cover-image.png'); cursor:pointer;"
                                data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-slide-index="0">
                            </div>
                            <div class="image rounded-1 m-1"
                                style=" background-image:url('/assets/images/profile/cover-image.png');cursor:pointer;"
                                data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-slide-index="1">
                            </div>
                            <div class="image rounded-1 m-1 d-flex justify-content-center align-items-center"
                                style="background-image:url('/assets/images/profile/cover-image.png'); filter:brightness(.5);cursor:pointer;"
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
                            style="background-image:url('/assets/images/profile/cover-image.png');">
                        </div>
                        <div class="image rounded-1 m-1"
                            style="background-image:url('/assets/images/profile/cover-image.png');">
                        </div>
                        <div class="image rounded-1 m-1 d-flex justify-content-center align-items-center"
                            style="background-image:url('/assets/images/profile/cover-image.png'); filter:brightness(.5);">
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
                        <img src="{{ asset('assets/images/profile/cover-image.png') }}" alt="UTeM"
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
                                style="background-image:url('/assets/images/profile/cover-image.png');"></div>
                            <div class="image rounded-1 m-1"
                                style="background-image:url('/assets/images/profile/cover-image.png');"></div>
                            <div class="image rounded-1 m-1 d-flex justify-content-center align-items-center"
                                style="background-image:url('/assets/images/profile/cover-image.png'); filter:brightness(.5);">
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
                            style="background-image:url('/assets/images/profile/cover-image.png');">
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

<div class="offcanvas m-auto" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel">
    <div class="offcanvas-header">
        <h3 class="offcanvas-title fw-bold" id="offcanvasBottomLabel">Add Section</h3>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body small">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Core
                    </button>
                </h2>

                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="list-group">
                        <a href="./education.html" class="list-group-item list-group-item-action">Education</a>
                        <a href="./project.html" class="list-group-item list-group-item-action">Project</a>
                        <a href="./experience.html" class="list-group-item list-group-item-action">Experience</a>
                        <a href="./honors&awards.html" class="list-group-item list-group-item-action">Honors &
                            Awards</a>
                        <a href="./certifications.html"
                            class="list-group-item list-group-item-action">Certifications</a>
                    </div>
                </div>

            </div>


            <div class="accordion-item">

                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Recommended
                    </button>
                </h2>


                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="list-group">
                        <a href="./languages.html" class="list-group-item list-group-item-action">
                            Languages
                        </a>
                        <a href="./skills.html" class="list-group-item list-group-item-action">
                            Skills
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark">

            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white">Project Highlights</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <div id="projectImageCarousel" class="carousel slide" data-bs-ride="false">
                    <div class="carousel-inner">

                        <div class="carousel-item active">
                            <img src="/assets/images/profile/cover-image.png" class="d-block w-100" alt="Preview 1">
                            <div class="carousel-caption d-block bg-dark bg-opacity-75 rounded p-2">
                                <h5>Title Image 1</h5>
                                <p class="d-none d-md-block">Description for image 1</p>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <img src="/assets/images/profile/cover-image.png" class="d-block w-100" alt="Preview 2">
                            <div class="carousel-caption d-block bg-dark bg-opacity-75 rounded p-2">
                                <h5>Title Image 2</h5>
                                <p class="d-none d-md-block">Description for image 2.</p>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <img src="/assets/images/profile/cover-image.png" class="d-block w-100" alt="Preview 3">
                            <div class="carousel-caption d-block bg-dark bg-opacity-75 rounded p-2">
                                <h5>Title Image 3</h5>
                                <p class="d-none d-md-block">Description for image 3.</p>
                            </div>
                        </div>

                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#projectImageCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#projectImageCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>

                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editAboutModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">

        <div class="modal-content">
            <form id="aboutForm" action="/profile/about" method="POST">
                <!-- @csrf -->
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-header">
                    <h5 class="modal-title">Edit About</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- LOADING -->
                    <div id="aboutLoading" class="text-center py-4 d-none">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                        <span class="ms-2">Loading...</span>
                    </div>

                    <!-- FORM -->
                    <div id="aboutFields">
                        <div class="mb-3">
                            <label for="aboutInput" class="form-label">About</label>
                            <textarea class="form-control" name="about" id="aboutInput" rows="6" maxlength="1000"
                                required></textarea>
                            <div class="text-end text-muted small mt-1">
                                <span id="aboutCount">0</span>/1000
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveAbout">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editProfileModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">

        <div class="modal-content">
            <form id="profileForm" action="/profile" method="POST">
                <!-- @csrf -->
                <input type="hidden" name="_method" value="PUT">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Intro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div id="profileLoading" class="text-center py-4 d-none">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                        <span class="ms-2">Loading...</span>
                    </div>

                    <div id="profileFields">
                        <div class="mb-3">
                            <label for="profileNameInput" class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" id="profileNameInput" required>
                        </div>

                        <div class="mb-3">
                            <label for="profileHeadlineInput" class="form-label">Headline</label>
                            <textarea class="form-control" name="headline" id="profileHeadlineInput" rows="3"
                                maxlength="255"></textarea>
                            <div class="text-end text-muted small mt-1">
                                <span id="headlineCount">0</span>/255
                            </div>
                        </div>
                        <hr>

                        <h6 class="fw-bold mb-3">Location</h6>
                        <div class="mb-3">
                            <label for="locationInput" class="form-label">Location</label>
                            <input type="text" id="locationInput" class="form-control">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveProfile">Save</button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function () {
        console.log("jQuery loaded");
        console.log($("#btnEditProfile").length);

        let resultLoaded = false;
        $(".profile-tab").on("click", function () {
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


        function loadSemesterResults() {

            $("#semesterResultList").html(`
					<div class="text-center py-4">
						<div class="spinner-border text-primary"></div>
						<p class="mt-2">Loading results...</p>
					</div>
				`);

            $.ajax({
                url: "../data/semester-results.json",
                type: "GET",
                dataType: "json",
                success: function (response) {
                    let html = "";
                    response.forEach(function (result) {
                        html += `
								<article class="semester-result-item py-3">
									<div class="d-flex justify-content-between align-items-center">
										<div>
											<p class="fw-bold mb-1">
												${result.semester}
											</p>
											<p class="text-muted mb-0">
												Session ${result.session}
											</p>
										</div>
										<a href="${result.file}"
											target="_blank"
											class="btn btn-outline-primary">
											<i class="fa-regular fa-file-pdf me-1"></i>
											View Result
										</a>
									</div>
								</article>
								<hr>`;
                    });

                    $("#semesterResultList").html(html);

                    resultLoaded = true;
                },

                error: function (xhr, status, error) {

                    console.error(error);

                    $("#semesterResultList").html(`
							<div class="alert alert-danger">
							Failed to load semester results.
							</div>
						`);
                }
            });
        }


        let profileModalEl = document.getElementById("editProfileModal");
        let profileModal = bootstrap.Modal.getOrCreateInstance(profileModalEl);
        let aboutModalEl = document.getElementById("editAboutModal");
        let aboutModal = bootstrap.Modal.getOrCreateInstance(aboutModalEl);

        let malaysiaStates = [
            {
                name: "Melaka",
                cities: ["Alor Gajah", "Asahan", "Ayer Keroh", "Bemban", "Durian Tunggal", "Jasin", "Kem Trendak", "Kuala Sungai Baru", "Lubok China", "Masjid Tanah", "Melaka", "Merlimau", "Selandar", "Sungai Rambai", "Sungai Udang", "Tanjong Kling"]
            }, {
                name: "Johor",
                cities: ["Batu Pahat", "Johor Bahru", "Kluang", "Kota Tinggi", "Kulai", "Mersing", "Muar", "Pontian", "Segamat", "Tangkak"]
            }, {
                name: "Selangor",
                cities: ["Shah Alam", "Petaling Jaya", "Subang Jaya", "Klang", "Sepang", "Kajang", "Gombak"]
            }
        ];

        let $countryInput = $("#countryInput");
        let $stateInput = $("#stateInput");
        let $cityInput = $("#cityInput");
        let $stateContainer = $("#stateContainer");
        let $cityContainer = $("#cityContainer");

        let $profileLoading = $("#profileLoading");
        let $profileFields = $("#profileFields");
        let $btnSaveProfile = $("#btnSaveProfile");

        let $aboutLoading = $("#aboutLoading");
        let $aboutFields = $("#aboutFields");
        let $btnSaveAbout = $("#btnSaveAbout");

        function renderStates(selectedState = "") {

            let html = `<option value="">Select State</option>`;

            malaysiaStates.forEach(
                function (state) {
                    let selected = state.name === selectedState ? "selected" : "";
                    html += `<option value="${state.name}" ${selected}> ${state.name} </option>`;
                }
            );
            $stateInput.html(html);
        }

        function renderCities(stateName, selectedCity = "") {
            let state = malaysiaStates.find(function (item) {
                return (item.name === stateName);
            });

            let html = `<option value="">Select City</option>`;
            if (state) {
                state.cities.forEach(function (city) {
                    let selected = city === selectedCity ? "selected" : "";
                    html += `<option value="${city}" ${selected}>
									${city}
								</option>`;

                });
            }

            $cityInput.html(html);
        }

        $countryInput.on("change", function () {
            let country = $(this).val();

            if (country === "Malaysia") {
                renderStates();
                $stateContainer.removeClass("d-none");

            } else {

                $stateContainer.addClass("d-none");
                $cityContainer.addClass("d-none");
                $stateInput.val("");
                $cityInput.val("");
            }

        });

        $stateInput.on("change", function () {
            let state = $(this).val();
            if (!state) {
                $cityContainer.addClass("d-none");
                return;
            }
            renderCities(state);
            $cityContainer.removeClass("d-none");
        });

        function showProfileLoading() {
            $profileLoading.removeClass("d-none");
            $profileFields.addClass("d-none");
            $btnSaveProfile.prop("disabled", true);
        }

        function hideProfileLoading() {
            $profileLoading.addClass("d-none");
            $profileFields.removeClass("d-none");
            $btnSaveProfile.prop("disabled", false);
        }

        $("#btnEditProfile").on("click", function () {
            showProfileLoading();

            $.ajax({
                url: "../data/profile.json",
                type: "GET",
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    /*
                    |----------------------------------------------
                    | Expected:
                    |
                    | {
                    |   "name": "Lorem bin Ipsum",
                    |   "headline": "...",
                    |   "location": "Durian Tunggal, Melaka, Malaysia"
                    | }
                    |----------------------------------------------
                    */
                    $("#profileNameInput").val(response.name ?? "");
                    $("#profileHeadlineInput").val(response.headline ?? "");
                    $("#headlineCount").text(
                        (response.headline ?? "").length
                    );


                    $countryInput.val(response.country ?? "");
                    if (response.country === "Malaysia") {

                        renderStates(response.state ?? "");
                        $stateContainer.removeClass("d-none");

                        if (response.state) {
                            renderCities(response.state, response.city ?? "");
                            $cityContainer.removeClass("d-none");
                        } else {
                            $cityContainer.addClass("d-none");
                        }
                    } else {
                        $stateContainer.addClass("d-none");
                        $cityContainer.addClass("d-none");
                    }

                    hideProfileLoading();
                    profileModal.show();
                },


                error: function (xhr) {
                    console.error(xhr);
                    hideProfileLoading();

                    Swal.fire({
                        title: "Unable to load profile",
                        text: "Profile data could not be loaded.",
                        icon: "error"
                    });
                }
            });
        });

        $("#profileForm").on("submit", function () {
            $btnSaveProfile.prop("disabled", true).text("Saving...");
        });

        $("#profileHeadlineInput").on("input", function () {
            $("#headlineCount").text(this.value.length);
        });

        function showAboutLoading() {
            $aboutLoading.removeClass("d-none");
            $aboutFields.addClass("d-none");
            $btnSaveAbout.prop("disabled", true);
        }

        function hideAboutLoading() {
            $aboutLoading.addClass("d-none");
            $aboutFields.removeClass("d-none");
            $btnSaveAbout.prop("disabled", false);
        }

        $("#btnEditAbout").on("click", function () {
            showAboutLoading();

            $.ajax({
                url: "../data/about.json",
                type: "GET",
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    /*
                    |----------------------------------------------
                    | Expected:
                    |
                    | {
                    |     "about": "Motivated..."
                    | }
                    |----------------------------------------------
                    */
                    $("#aboutInput").val(response.about ?? "");
                    $("#aboutCount").text((response.about ?? "").length);
                    hideAboutLoading();
                    aboutModal.show();
                },

                error: function (xhr) {
                    console.error(xhr);
                    hideAboutLoading();
                    Swal.fire({
                        title: "Unable to load about",
                        text: "About information could not be loaded.",
                        icon: "error"
                    });
                }
            });
        });

        $("#aboutForm").on("submit", function () {
            $btnSaveAbout.prop("disabled", true).text("Saving...");
        });

        $("#aboutInput").on("input", function () {
            $("#aboutCount").text(this.value.length);
        });

        profileModalEl.addEventListener("hidden.bs.modal", function () {
            $("#profileForm")[0].reset();
            $("#headlineCount").text("0");

            $stateContainer.addClass("d-none");
            $cityContainer.addClass("d-none");
            $btnSaveProfile.prop("disabled", false).text("Save");
        });

        aboutModalEl.addEventListener("hidden.bs.modal", function () {
            $("#aboutForm")[0].reset();
            $("#aboutCount").text("0");

            $btnSaveAbout.prop("disabled", false).text("Save");

        });

        $(document).on("click", "[data-slide-index]", function () {

            let index = Number($(this).data("slide-index"));
            let carouselEl = document.getElementById("projectImageCarousel");
            let carousel = bootstrap.Carousel.getOrCreateInstance(carouselEl);
            carousel.to(index);
        });


        function adjustOffcanvasPosition() {

            let offcanvasEl = document.getElementById("offcanvasBottom");
            if (window.innerWidth >= 768) {

                offcanvasEl.classList.remove("offcanvas-bottom");
                offcanvasEl.classList.add("offcanvas-end");
                offcanvasEl.style.height = "100vh";
                offcanvasEl.style.width = "400px";

            } else {

                offcanvasEl.classList.remove("offcanvas-end");
                offcanvasEl.classList.add("offcanvas-bottom");
                offcanvasEl.style.height = "60vh";
                offcanvasEl.style.width = "100%";

            }

        }
        adjustOffcanvasPosition();
        window.addEventListener("resize", adjustOffcanvasPosition);
    });
</script>
@endsection
