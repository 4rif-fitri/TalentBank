<style>
    .profile-actions {
        width: 100%;
    }

    .profile-action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    @media (min-width: 768px) {
        .profile-actions {
            width: 25%;
            flex-shrink: 0;
            align-content: flex-start;
        }
    }
</style>

<section class="user-card-header">
    <div class="user-card-banner" id="coverImage" style="background-image: url('');">
        <button class="icon-container btn" data-bs-toggle="tooltip" data-bs-placement="bottom"
            data-bs-custom-class="custom-tooltip" data-bs-title="Edit Cover Image">>
            <label for="coverImageInput" class="btn icon bg-body">
                <i class="fa-solid fa-pencil"></i>
                <input type="file" hidden id="coverImageInput" name="cover_image" accept="image/*">
            </label>
        </button>
    </div>

    <div class="user-profile-group">
        <div class="profile-image" id="profileImage" style="background-image: url('');">
            <div class="w-100 h-100 position-relative">
                <label for="profileImageInput" class="btn icon bg-body">
                    <i class="fa-solid fa-camera" data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-custom-class="custom-tooltip" data-bs-title="Edit Image Profile"></i>
                    <input type="file" hidden id="profileImageInput" accept="image/*">
                </label>
            </div>
        </div>
    </div>

    <div class="d-md-flex justify-content-md-between">
        <div class="user-profile-detail">
            <!-- <div class="icon-container" style="top:-25px;">
                <button type="button" class="btn btn-secondary icon" id="btnEditProfile">
                    <i class="fa-solid fa-pencil"></i>
                </button>
            </div> -->
            <p id="name" class="fw-bold lg-h2 sm-h6"></p>
            <button type="button" class="btn btn-secondary icon" id="btnEditProfile" data-bs-toggle="tooltip"
                data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Edit Profile Data">
                <i class="fa-solid fa-pencil"></i>
            </button>
            <p id="headline"></p>
            <a id="uni-name" href="#" class="h6 fw-bold mb-0"></a>
            <p id="programme"></p>
            <button id="seeMoreActiveEducations" type="button" class="btn badge text-bg-primary"
                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip"
                data-bs-title="See More Active Educations">
                See More
            </button>
            <article id="location">
                <p>
                    <i class="fa-solid fa-location-dot"></i>
                    <span id="profileLocation"></span>
                </p>
            </article>

            <x-links />

        </div>

        <div class="row g-2 profile-actions mt-lg-4 mt-md-4">
            <div class="col-12 order-1 order-md-2">
                <button class="btn btn-primary w-100 profile-action-btn" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom">
                    <i class="fa-solid fa-plus"></i>
                    Add Section
                </button>
            </div>
            <div class="col-6 col-md-12 order-2 order-md-1">
                <button class="btn btn-outline-primary w-100 profile-action-btn" type="button">
                    <i class="fa-regular fa-eye"></i>
                    Preview
                </button>
            </div>
            <div class="col-6 col-md-12 order-3 order-md-3">
                <button class="btn btn-outline-primary w-100 profile-action-btn" type="button">
                    <i class="fa-solid fa-share-nodes"></i>
                    Share
                </button>
            </div>

        </div>
    </div>

    <nav class="horizontal-nav d-flex flex-wrap px-3">
        <x-button-horizontal-nav target="main" class="active">
            Main
        </x-button-horizontal-nav>

        <x-button-horizontal-nav target="result">
            Result
        </x-button-horizontal-nav>

        <x-button-horizontal-nav target="education">
            Education
        </x-button-horizontal-nav>

        <x-button-horizontal-nav target="awards">
            Awards
        </x-button-horizontal-nav>

        <x-button-horizontal-nav target="projects">
            Projects
        </x-button-horizontal-nav>

        <x-button-horizontal-nav target="experience">
            Experience
        </x-button-horizontal-nav>

        <x-button-horizontal-nav target="certifications">
            Certifications
        </x-button-horizontal-nav>
    </nav>

</section>
