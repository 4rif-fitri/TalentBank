<section class="user-card-header">
    <div class="user-card-banner" id="coverImage" style="background-image: url('../assets/internship-assets/images/7.jpg');">
        <div class="icon-container">
            <label for="coverImageInput" class="btn icon bg-body">
                <i class="fa-solid fa-pencil"></i>
                <input type="file" hidden id="coverImageInput" name="cover_image" accept="image/*">
            </label>
        </div>
    </div>

    <div class="user-profile-group">
        <div class="profile-image" id="profileImage" style="background-image: url('../assets/internship-assets/images/7.jpg');">
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
        <h2 id="name" class="fw-bold"></h2>
        <p id="headline">Computer Science Student | Web Developer | UI/UX Enthusiast</p>
        <a id="uni-name" href="#" class="h5 fw-bold">Universiti Teknikal Malaysia Melaka (UTeM)</a>
        <p id="programme">Bachelor of Computer Science (Software Engineering)</p>

        <article id="location">
            <p>
                <i class="fa-solid fa-location-dot"></i>
                <span id="profileLocation"></span>|
                <i class="fa-solid fa-user-check verified"></i>
                University Verified
            </p>
        </article>

        <x-links />

        <div class="btn-container">

            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                aria-controls="offcanvasBottom">
                Add Section
            </button>

        </div>

    </div>

    <nav class="horizontal-nav d-flex flex-wrap px-3">
        <button type="button" class="profile-tab active d-flex justify-content-center mt-3" data-target="main">
            Main
        </button>
        <button type="button" class="profile-tab d-flex justify-content-center mt-3" data-target="result">
            Result
        </button>
        <button type="button" class="profile-tab d-flex justify-content-center mt-3" data-target="education">
            Education
        </button>
        <button type="button" class="profile-tab d-flex justify-content-center mt-3" data-target="awards">
            Awards
        </button>
        <button type="button" class="profile-tab d-flex justify-content-center mt-3" data-target="projects">
            Projects
        </button>
        <button type="button" class="profile-tab d-flex justify-content-center mt-3" data-target="experience">
            Experience
        </button>
        <button type="button" class="profile-tab d-flex justify-content-center mt-3" data-target="certifications">
            Certifications
        </button>
    </nav>

</section>

@push('scripts')
    <script></script>
@endpush
