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

                    <div class="user-profile-ditail">
                        <div class="icon-container">
                            <button type="button" class="btn btn-primary waves-effect icon" data-toggle="modal"
                                data-target="#userDetailModal">
                                <i class="ti-pencil ti-md"></i>
                            </button>
                        </div>

                        <h2 id="name">Lorem bin Ipsum</h2>
                        <p id="headline">Computer Science Student | Web Developer | UI/UX Enthusiast</p>
                        <a href="" id="uni-name">
                            <h5>Universiti Teknikal Malaysia Melaka (UTeM)</h5>
                        </a>
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
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                Launch demo modal
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
                        <div class="waves-effect icon">
                            <i class="ti-pencil ti-md"></i>
                        </div>
                    </div>
                </section>

                <section id="education" class="d-flex flex-column gap-1">
                    <h2>Education</h2>

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
                            <img src="{{ asset('assets/images/profile/utemjpg.jpg') }}" alt="" style="width: 50px !important; border-radius: 50%;">
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
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>Software Engineering</span>
                                </a>
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>System Analysis</span>
                                </a>
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>Database Design</span>
                                </a>
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>Web Development</span>
                                </a>
                            </div>

                            <div class="images d-flex flex-wrap">
                                <div class="image rounded-5 mx-1"
                                    style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}');">
                                </div>
                                <div class="image rounded-5 mx-1"
                                    style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}');">
                                </div>
                                <div class="image rounded-5 m-1 d-flex justify-content-center align-items-center"
                                    style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}'); filter: brightness(0.5);">
                                    <h4 class="text-white">+5</h4>
                                </div>
                            </div>
                        </div>

                    </article>

                    <hr>

                    <a href="" class="d-flex justify-content-center align-items-center">
                        <h4>Show All</h4>
                    </a>

                </section>

                <section id="projects" class="d-flex flex-column gap-1">
                    <h2>projects</h2>

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
                        <h4>EduMaths – Complements to 10 Learning Platform</h4>
                        <p>Jul 2026 - Aug 2026 (Part-time)</p>

                        <p>Designed an interactive mathematics learning platform for primary school
                            pupils.
                            The system teaches complements to 10 through modular lessons, quizzes,
                            number bonds,
                            custom number pads and immediate answer feedback.</p>

                        <div class="skills">
                            <a href="" class="waves-effect bg-secondary">
                                <i class="fa-brands fa-laravel"></i>
                                <span>Laravel</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary">
                                <span>Vue</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary">
                                <span>React</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary">
                                <span>Html</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary">
                                <span>Css</span>
                            </a>
                        </div>

                        <div class="images d-flex flex-wrap">
                            <div class="image rounded-5 mx-1"
                                style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}');">
                            </div>
                            <div class="image rounded-5 mx-1"
                                style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}');">
                            </div>
                            <div class="image rounded-5 m-1 d-flex justify-content-center align-items-center"
                                style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}'); filter: brightness(0.5);">
                                <h4 class="text-white">+5</h4>
                            </div>
                        </div>

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
                            <img src="{{ asset('assets/images/profile/utemjpg.jpg') }}" alt="" style="width: 50px !important; border-radius: 50%;">
                        </div>
                        <div class="col d-flex flex-column mx-1">
                            <h4>Web Developer Intern</h4>
                            <a href="">Wahdah Technology - Internship</a>
                            <p>Jul 2026 - Present</p>
                            <p>Melaka, Malaysia - On-site</p>
                            <p>Contributed to Laravel-based web applications, database planning,
                                responsive UI design</p>

                            <div class="skills d-flex flex-wrap align-items-center">
                                Skills:
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>Laravel</span>
                                </a>
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>JavaScript</span>
                                </a>
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>MySQL</span>
                                </a>
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>Bootstrap</span>
                                </a>
                            </div>

                            <div class="images d-flex flex-wrap">
                                <div class="image rounded-5 mx-1"
                                    style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}');">
                                </div>
                                <div class="image rounded-5 mx-1"
                                    style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}');">
                                </div>
                                <div class="image rounded-5 m-1 d-flex justify-content-center align-items-center"
                                    style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}'); filter: brightness(0.5);">
                                    <h4 class="text-white">+5</h4>
                                </div>
                            </div>
                        </div>

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
                        <h4>Dean's List Award</h4>
                        <p>Issued Oct 2025</p>
                        <!-- <div class="d-flex align-items-center company-group">
             <img src="./utemjpg.jpg" alt="" style="width: 50px; border-radius: 50%;"> -->
                        <p>Associated with Universiti Teknikal Malaysia Melaka</p>
                        <!-- </div> -->

                        <p>Recognised for strong academic performance in the Diploma in Computer Science
                            programme, including excellent results in database, web programming and
                            system analysis.</p>

                        <div class="skills d-flex flex-wrap align-items-center">
                            Skills:
                            <a href="" class="waves-effect bg-secondary m-1">
                                <span>Academic Excellence</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary m-1">
                                <span>Problem Solving</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary m-1">
                                <span>Database</span>
                            </a>
                            <a href="" class="waves-effect bg-secondary m-1">
                                <span>Web Programming</span>
                            </a>
                        </div>

                        <div class="images d-flex flex-wrap">
                            <div class="image rounded-5 mx-1" style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}');">
                            </div>
                            <div class="image rounded-5 mx-1" style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}');">
                            </div>
                            <div class="image rounded-5 m-1 d-flex justify-content-center align-items-center"
                                style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}'); filter: brightness(0.5);">
                                <h4 class="text-white">+5</h4>
                            </div>
                        </div>

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

                    <section class="m-1">
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
                                <a href="" class="waves-effect bg-secondary m-1">
                                    <span>Oracle Database</span>
                                </a>
                            </div>

                            <div class="images d-flex flex-wrap">
                                <div class="image rounded-5 mx-1" style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}');">
                                </div>
                                <div class="image rounded-5 mx-1" style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}');">
                                </div>
                                <div class="image rounded-5 m-1 d-flex justify-content-center align-items-center"
                                    style="background-image: url('{{ asset('assets/images/profile/cover-image.png') }}'); filter: brightness(0.5);">
                                    <h4 class="text-white">+5</h4>
                                </div>
                            </div>
                        </div>

                    </section>

                    <hr>

                    <a href="" class="d-flex justify-content-center align-items-center">
                        <h4>Show All</h4>
                    </a>

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
                            <h4>Bahasa Melayu</h4>
                            <p>Native</p>
                        </div>
                    </article>
                    <article class="m-1">
                        <div class="col d-flex justify-content-between m-1" style="text-align: end">
                            <h4>English</h4>
                            <p>Professional working proficiency</p>
                        </div>
                    </article>
                    <!-- <hr>

           <a href="" class="d-flex justify-content-center align-items-center">
            <h4>Show All</h4>
           </a> -->

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
                            <h4>
                                <i class="fa-solid fa-database"></i>
                                Database
                            </h4>
                            <p>Advanced</p>
                        </div>
                    </article>

                    <article class="m-1">
                        <div class="col d-flex justify-content-between m-1">
                            <h4>
                                <i class="fa-brands fa-html5"></i>
                                HTML
                            </h4>
                            <p>Advanced</p>
                        </div>
                    </article>
                    <!-- <hr> -->
                    <!-- <a href="" class="d-flex justify-content-center align-items-center">
            <h4>Show All</h4>
           </a> -->

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

	<div class="modal fade" id="userDetailModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
		aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLabel">Edit intro</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p class="required">* Indicates required</p>
					<h4>Basic info</h4>
					<form action="" class="d-flex flex-column mt-2">

						<div class="form-group">
							<label for="headlineInput">Headline</label>
							<textarea class="form-control" name="headlineInput" id="headlineInput"></textarea>
						</div>

						<section id="locationInputs">
							<h4>Location</h4>

							<div class="d-grid">

								<div class="row">
									<div class="form-group col">
										<label for="countryInput">Country/Region*</label>
										<select class="form-control" id="countryInput" name="countryInput">
											<option selected disabled>Select Country</option>
											<option value="Malaysia">Malaysia</option>
										</select>
									</div>

									<div class="form-group col">
										<label for="poscodeInput">Poscode</label>
										<input type="number" name="poscodeInput" class="form-control"
											id="poscodeInput">
									</div>

									<div class="form-group col">
										<label for="cityInput">City*</label>
										<select class="form-control" id="cityInput" name="cityInput">
											<option selected disabled>Select City</option>
											<option value="Kulai">Kulai</option>
										</select>
									</div>
								</div>
							</div>

						</section>

						<section id="socialMediaInputs" class="d-flex flex-column" style="gap: 0.5rem;">
							<div class="d-flex justify-content-between">
								<h4>Social Media Links</h4>
								<button type="button" class="btn btn-primary">Add Link</button>
							</div>

							<div class="input-group social-group">
								<select class="form-select" name="social_platform" id="socialPlatform">
									<option value="" selected disabled>Select App</option>
									<option value="linkedin">LinkedIn</option>
									<option value="github">GitHub</option>
									<option value="youtube">YouTube</option>
									<option value="facebook">Facebook</option>
									<option value="instagram">Instagram</option>
									<option value="tiktok">TikTok</option>
									<option value="twitter">X (Twitter)</option>
								</select>
								<input type="url" class="form-control" name="social_url" id="socialUrl"
									placeholder="https://example.com/username">
								<button type="button" class="btn btn-outline-danger">
									<i class="fa-solid fa-trash"></i>
								</button>
							</div>
						</section>


					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save</button>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('script')
    <script></script>
@endsection
