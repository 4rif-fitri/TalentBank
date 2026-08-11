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
                                <div class="waves-effect icon">
                                    <i class="ti-camera ti-md"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="user-profile-detail">
                        <div class="icon-container">
                            <button data-type="user-profile-detail" type="button" class="btn btn-primary waves-effect icon"
                                data-toggle="modal" data-target="#profileDetailModal">
                                <i class="ti-pencil ti-md"></i>
                            </button>
                        </div>

                        <h2 id="name">Lorem bin Ipsum</h2>
                        <p id="headline">Computer Science Student | Web Developer | UI/UX Enthusiast</p>
                        <div href="" id="uni-name">
                            <h5>Universiti Teknikal Malaysia Melaka (UTeM)</h5>
                        </div>
                        <p id="programe">Bachelor of Computer Science (Software Engineering)</p>

                        <article id="location">
                            <p>
                                <i class="ti-location-pin ti-md"></i>
                                Melaka, Malaysia |
                                <i class="fa-solid fa-user-check verified"></i>
                                University Verified
                            </p>
                        </article>

                        <article class="links">
                            <a href="https://youtube.com/@ariffitri" target="_blank" rel="noopener">
                                <i class="fa-brands fa-youtube"></i>
                                Youtube
                            </a>
                            <a href="https://facebook.com/ariffitri" target="_blank" rel="noopener">
                                <i class="fa-brands fa-facebook"></i>
                                FaceBook
                            </a>
                            <a href="https://instagram.com/ariffitri" target="_blank" rel="noopener">
                                <i class="fa-brands fa-instagram"></i>
                                Instagram
                            </a>
                            <a href="https://tiktok.com/@ariffitri" target="_blank" rel="noopener">
                                <i class="fa-brands fa-tiktok"></i>
                                Tiktok
                            </a>
                            <a href="https://linkedin.com/in/arif-fitri" target="_blank" rel="noopener">
                                <i class="fa-brands fa-linkedin"></i>
                                Linkedin
                            </a>
                            <a href="https://github.com/4rif-fitri" target="_blank" rel="noopener">
                                <i class="fa-brands fa-github"></i>
                                Github
                            </a>
                            <a href="https://x.com/ariffitri" target="_blank" rel="noopener">
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

                    <nav class="horizontal-nav">
                        <ul>
                            <li class="nav-item active">Main</li>
                            <li class="nav-item">Result Sem</li>
                        </ul>
                    </nav>
                </div>

                <section id="about">
                    <h2>About</h2>
                    <p>Motivated computer science student with experience in web development, database
                        design and user interface prototyping. Interested in building responsive and
                        user-friendly applications using Laravel, JavaScript and modern frontend tools.
                    </p>

                    <div class="icon-container">
                        <button data-type="about" data-toggle="modal" data-target="#aboutModal"
                            class="waves-effect icon">
                            <i class="ti-pencil ti-md"></i>
                        </button>
                    </div>
                </section>

                <section id="education" class="d-flex flex-column gap-1">
                    <h2>Education</h2>

                    <div class="icon-container">
                        <button data-toggle="modal" data-target="#educationModal" class="waves-effect icon">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                        <a href="" class="icon">
                            <i class="ti-pencil ti-md"></i>
                        </a>
                    </div>
                    <hr>

                    <article class="m-1">
                        <div>
                            <img src="./utemjpg.jpg" alt="" style="width: 50px !important; border-radius: 50%;">
                        </div>
                        <div class="col d-flex flex-column mx-1">
                            <h4>Universiti Teknikal Malaysia Melaka (UTeM)</h4>
                            <p>Bachelor of Computer Science (Software Engineering)</p>
                            <p>Oct 2026 - Oct 2027</p>
                            <p>Grade: 4.00</p>
                            <p>Currently pursuing a degree in software engineering with emphasis on
                                system
                                analysis, web application development, databases and software project
                                management.</p>

                            <div class="skills d-flex flex-wrap align-items-center">
                                Skills:
                                <div href="" class="waves-effect bg-secondary m-1">
                                    <span>Software Engineering</span>
                                </div>
                                <div href="" class="waves-effect bg-secondary m-1">
                                    <span>System Analysis</span>
                                </div>
                                <div href="" class="waves-effect bg-secondary m-1">
                                    <span>Database Design</span>
                                </div>
                                <div href="" class="waves-effect bg-secondary m-1">
                                    <span>Web Development</span>
                                </div>
                            </div>

                            <div class="images d-flex flex-wrap">
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

                    </article>

                    <hr>

                    <div href="" class="d-flex justify-content-center align-items-center">
                        <h4>Show All</h4>
                    </div>

                </section>

                <section id="projects" class="d-flex flex-column gap-1">
                    <h2>Project</h2>

                    <div class="icon-container">
                        <button data-toggle="modal" data-target="#educationModal" class="waves-effect icon">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                        <a href="" class="icon">
                            <i class="ti-pencil ti-md"></i>
                        </a>
                    </div>

                    <hr>

                    <article class="m-1 d-flex flex-column gap-2">
                        <h4>EduMaths – Complements to 10 Learning Platform</h4>
                        <p>Jul 2026 - Aug 2026 (Part-time)</p>

                        <p>Designed an interactive mathematics learning platform for primary school
                            pupils.
                            The system teaches complements to 10 through modular lessons, quizzes,
                            number bonds,
                            custom number pads and immediate answer feedback.</p>

                        <div class="skills">
                            <div href="" class="waves-effect bg-secondary">
                                <span>Laravel</span>
                            </div>
                            <div href="" class="waves-effect bg-secondary">
                                <span>Vue</span>
                            </div>
                            <div href="" class="waves-effect bg-secondary">
                                <span>React</span>
                            </div>
                            <div href="" class="waves-effect bg-secondary">
                                <span>Html</span>
                            </div>
                            <div href="" class="waves-effect bg-secondary">
                                <span>Css</span>
                            </div>
                        </div>

                        <div class="images d-flex flex-wrap">
                            <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');"></div>
                            <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');"></div>
                            <div class="image rounded-5 m-1 d-flex justify-content-center align-items-center"
                                style="background-image: url('./cover-image.png'); filter: brightness(0.5);">
                                <h4 class="text-white">+5</h4>
                            </div>
                        </div>

                        <hr>
                    </article>

                    <div href="" class="d-flex justify-content-center align-items-center">
                        <h4>Show All</h4>
                    </div>

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
                            <h4>Web Developer Intern</h4>
                            <div href="">Wahdah Technology - Internship</div>
                            <p>Jul 2026 - Present</p>
                            <p>Melaka, Malaysia - On-site</p>
                            <p>Contributed to Laravel-based web applications, database planning,
                                responsive UI design</p>

                            <div class="skills d-flex flex-wrap align-items-center">
                                Skills:
                                <div href="" class="waves-effect bg-secondary m-1">
                                    <span>Laravel</span>
                                </div>
                                <div href="" class="waves-effect bg-secondary m-1">
                                    <span>JavaScript</span>
                                </div>
                                <div href="" class="waves-effect bg-secondary m-1">
                                    <span>MySQL</span>
                                </div>
                                <div href="" class="waves-effect bg-secondary m-1">
                                    <span>Bootstrap</span>
                                </div>
                            </div>

                            <div class="images d-flex flex-wrap">
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

                    <div href="" class="d-flex justify-content-center align-items-center">
                        <h4>Show All</h4>
                    </div>

                </section>

                <section id="Honors_awards" class="d-flex flex-column gap-1">
                    <h2>Honors & Awards </h2>

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
                        <h4>Dean's List Award</h4>
                        <p>Issued by FTMK UTeM · Academic Session 2024/2025</p>

                        <!-- <div class="d-flex align-items-center company-group">
                         <img src="./utemjpg.jpg" alt="" style="width: 50px; border-radius: 50%;"> -->
                        <p>Associated with Universiti Teknikal Malaysia Melaka</p>
                        <!-- </div> -->

                        <p>Recognised for strong academic performance in the Diploma in Computer Science
                            programme, including excellent results in database, web programming and
                            system analysis.</p>

                        <div class="skills d-flex flex-wrap align-items-center">
                            Skills:
                            <div href="" class="waves-effect bg-secondary m-1">
                                <span>Academic Excellence</span>
                            </div>
                            <div href="" class="waves-effect bg-secondary m-1">
                                <span>Problem Solving</span>
                            </div>
                            <div href="" class="waves-effect bg-secondary m-1">
                                <span>Database</span>
                            </div>
                            <div href="" class="waves-effect bg-secondary m-1">
                                <span>Web Programming</span>
                            </div>
                        </div>

                        <div class="images d-flex flex-wrap">
                            <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');"></div>
                            <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');"></div>
                            <div class="image rounded-5 m-1 d-flex justify-content-center align-items-center"
                                style="background-image: url('./cover-image.png'); filter: brightness(0.5);">
                                <h4 class="text-white">+5</h4>
                            </div>
                        </div>

                        <hr>
                    </article>

                    <div href="" class="d-flex justify-content-center align-items-center">
                        <h4>Show All</h4>
                    </div>

                </section>

                <section id="certifications" class="d-flex flex-column gap-1">
                    <h2>Certifications </h2>

                    <div class="icon-container">
                        <div class="icon">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div class="icon">
                            <i class="ti-pencil ti-md"></i>
                        </div>
                    </div>

                    <hr>

                    <div class="m-1">
                        <div>
                            <!-- <img src="./utemjpg.jpg" alt=""
                          style="width: 50px !important; border-radius: 50%;"> -->
                        </div>
                        <div class="col d-flex flex-column mx-1">
                            <h4>Oracle Certified Foundations Associate, Database</h4>
                            <p>Oracle Academy</p>
                            <p>Issued Oct 2025 · Credential ID: OCFA-ARIF-2025</p>
                            <a class="btn d-flex justify-content-center align-items-center" target="_blank"
                                rel="noopener"
                                style="border: 1px solid gray; border-radius: 14px; color: gray; width: 150px;"
                                href="">
                                <p>Show credential</p>
                                <i class="fa-solid fa-up-right-from-square mx-1"></i>
                            </a>

                            <div class="skills d-flex flex-wrap align-items-center">
                                Skills:
                                <div href="" class="waves-effect bg-secondary m-1">
                                    <span>Oracle Database</span>
                                </div>
                            </div>

                            <div class="images d-flex flex-wrap">
                                <div class="image rounded-5 m-1" style="background-image: url('./cover-image.png');">
                                </div>
                            </div>
                        </div>

                        <hr>

                    </div>

                    <div href="" class="d-flex justify-content-center align-items-center">
                        <h4>Show All</h4>
                    </div>

                </section>

            </div>

            <div class="user-card-right">

                <section id="languages" class="d-flex flex-column gap-1">
                    <h2>Languages</h2>

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
                            <h5>Bahasa Melayu</h5>
                            <p>Native</p>
                        </div>
                    </article>
                    <article class="m-1">
                        <div class="col d-flex justify-content-between m-1">
                            <h5>English</h5>
                            <p style="text-align: end;">Professional working proficiency</=>
                        </div>
                    </article>

                </section>

                <section id="skills" class="d-flex flex-column gap-1">
                    <h2>Skills</h2>
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
                            <h5>
                                <i class="fa-solid fa-database"></i>
                                Database
                            </h5>
                            <p>Advanced</p>
                        </div>
                    </article>

                    <article class="m-1">
                        <div class="col d-flex justify-content-between m-1">
                            <h5>
                                <i class="fa-brands fa-html5"></i>
                                HTML
                            </h5>
                            <p>Advanced</p>
                        </div>
                    </article>

                </section>
            </div>

        </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="profileDetailModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Edit Intro</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="profileDetailModalForm" class="d-flex flex-column mt-2">

                        <div class="form-group">
                            <label for="nameInput">
                                <h4>Full Name</h4>
                            </label>
                            <input type="text" class="form-control" name="nameInput" id="nameInput"></input>
                        </div>

                        <div class="form-group">
                            <label for="headlineInput">
                                <h4>Headline</h4>
                            </label>
                            <textarea class="form-control" name="headlineInput" id="headlineInput"></textarea>
                        </div>

                        <section id="locationInputs">
                            <h4>Location</h4>
                            <div class="d-grid">
                                <div class="row">
                                    <div class="form-group col-4">
                                        <label for="countryInput">Country/Region*</label>
                                        <select class="form-control" id="countryInput" name="countryInput">
                                            <option selected disabled>Select Country</option>
                                            <option value="Malaysia">Malaysia</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-4">
                                        <div id="stateContainer">
                                            <label for="stateInput">State*</label>
                                            <select class="form-control" id="stateInput" name="stateInput">
                                                <option selected disabled>Select State</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-4">
                                        <div id="cityContainer">
                                            <label for="cityInput">City*</label>
                                            <select class="form-control" id="cityInput" name="cityInput">
                                                <option selected disabled>Select City</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="socialMediaInputs" class="d-flex flex-column" style="gap: 0.1rem;">
                            <div class="d-flex justify-content-between">
                                <h4>Social Media Links</h4>
                                <button type="button" class="btn btn-primary" id="addLinkBtn">Add Link</button>
                            </div>

                            <div class="input-group social-group">

                            </div>
                        </section>

                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnCloseEditIntro" class="btn btn-secondary" type="reset"
                        data-dismiss="modal">Close</button>
                    <button id="btnSaveEditIntro" class="btn btn-primary" type="submit">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#vertical-menu-btn').on('click', function(event) {
            event.preventDefault();
            $('body').toggleClass('sidebar-enable');
            if ($(window).width() >= 992) {
                $('body').toggleClass('vertical-collpsed');
            } else {
                $('body').removeClass('vertical-collpsed');
            }
        });

        $(document).ready(function() {

            $("#stateContainer").hide();
            $("#cityContainer").hide();

            const dataMalaysia = [{
                    "name": "Melaka",
                    "city": ["Alor Gajah", "Asahan", "Ayer Keroh", "Bemban", "Durian Tunggal", "Jasin",
                        "Kem Trendak", "Kuala Sungai Baru", "Lubok China", "Masjid Tanah", "Melaka",
                        "Merlimau", "Selandar", "Sungai Rambai", "Sungai Udang", "Tanjong Kling"
                    ]
                },
                {
                    "name": "Johor",
                    "city": ["Batu Pahat", "Johor Bahru", "Kluang", "Kota Tinggi", "Kulai", "Mersing", "Muar",
                        "Pontian", "Segamat", "Tangkak"
                    ]
                },
                {
                    "name": "Selangor",
                    "city": ["Shah Alam", "Petaling Jaya", "Subang Jaya", "Klang", "Sepang", "Kajang", "Gombak"]
                }
            ];

            $("#countryInput").on("change", function() {
                if ($(this).val() === "Malaysia") {
                    $("#stateContainer").show();

                    let stateOptions = '<option selected disabled>Select State</option>';
                    dataMalaysia.forEach(negeri => {
                        stateOptions += `<option value="${negeri.name}">${negeri.name}</option>`;
                    });
                    $("#stateInput").html(stateOptions);

                    $("#cityContainer").hide();
                    $("#cityInput").html('<option selected disabled>Select City</option>');
                } else {
                    $("#stateContainer").hide();
                    $("#cityContainer").hide();
                }
            });

            $("#stateInput").on("change", function() {
                let selectedState = $(this).val();

                let cariNegeri = dataMalaysia.find(negeri => negeri.name === selectedState);

                if (cariNegeri) {
                    $("#cityContainer").show();

                    let cityOptions = '<option selected disabled>Select City</option>';
                    cariNegeri.city.forEach(bandar => {
                        cityOptions += `<option value="${bandar}">${bandar}</option>`;
                    });
                    $("#cityInput").html(cityOptions);
                }
            });

            $("#addLinkBtn").on("click", () => {
                let newField = `
					<div class="input-group social-group mt-1">
						<select class="form-select" name="social_platform[]">
							<option value="" selected disabled>Select App</option>
							<option value="1">LinkedIn</option>
							<option value="2">GitHub</option>
							<option value="3">YouTube</option>
							<option value="4">Facebook</option>
							<option value="5">Instagram</option>
							<option value="6">TikTok</option>
							<option value="7">X (Twitter)</option>
						</select>
						<input type="url" class="form-control" name="social_url[]" placeholder="https://example.com/username">
						<button type="button" class="btn btn-outline-danger remove-btn">
							<i class="fa-solid fa-trash"></i>
						</button>
					</div>
				`;
                $("#socialMediaInputs").append(newField);
            });

            $("#socialMediaInputs").on("click", ".remove-btn", function() {
                $(this).closest(".social-group").remove();
            });

            // btn close modal
            $("#btnCloseEditIntro").on("click", () => {
                $("#profileDetailModalForm")[0].reset();
                $("#socialMediaInputs .social-group:not(:first)").remove();
                $("#stateContainer").hide();
                $("#cityContainer").hide();
            });

            // btn save modal
            $("#btnSaveEditIntro").on("click", function(e) {
                e.preventDefault();

                let requestData = {
                    nameInput: $("#nameInput").val(),
                    headlineInput: $("#headlineInput").val(),
                    countryInput: $("#countryInput").val(),
                    negeriInput: $("#stateInput").val(),
                    cityInput: $("#cityInput").val(),
                    social_media: []
                };

                // get data social media links
                $("#socialMediaInputs .social-group").each(function() {
                    let selectedPlatform = $(this).find("select[name='social_platform[]']").val();
                    let inputtedUrl = $(this).find("input[name='social_url[]']").val();

                    if (selectedPlatform && inputtedUrl) {
                        requestData.social_media.push({
                            platform: selectedPlatform,
                            url: inputtedUrl
                        });
                    }
                });

                console.log(requestData)

                let $btn = $(this);
                let originalText = $btn.text();
                $btn.text("Saving...").prop("disabled", true);

                $.ajax({
                    url: '',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(requestData),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#userDetailModal').modal('hide');
                        } else {
                            alert("Gagal menyimpan data: " + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", error);
                    },
                    complete: function() {
                        $btn.text(originalText).prop("disabled", false);
                    }
                });
            });

        });
    </script>
@endsection
