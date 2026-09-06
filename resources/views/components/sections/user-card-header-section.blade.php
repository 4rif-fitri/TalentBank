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

        @if (array_intersect(session('roles') ?? [], ['Student']))
        <button class="icon-container btn" data-bs-toggle="tooltip" data-bs-placement="bottom"
            data-bs-custom-class="custom-tooltip" data-bs-title="Edit Cover Image">>
            <label for="coverImageInput" class="btn icon bg-body">
                <i class="fa-solid fa-pencil"></i>
                <input type="file" hidden id="coverImageInput" name="cover_image" accept="image/*">
            </label>
        </button>
        @endif

    </div>

    <div class="user-profile-group">
        <div class="profile-image" id="profileImage" style="background-image: url('');">

            @if (array_intersect(session('roles') ?? [], ['Student']))
            <div class="w-100 h-100 position-relative">
                <label for="profileImageInput" class="btn icon bg-body">
                    <i class="fa-solid fa-camera" data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-custom-class="custom-tooltip" data-bs-title="Edit Image Profile"></i>
                    <input type="file" hidden id="profileImageInput" accept="image/*">
                </label>
            </div>
            @endif

        </div>
    </div>

    <div class="d-md-flex justify-content-md-between">
        <div class="user-profile-detail">

            <p id="name" class="fw-bold lg-h2 sm-h6"></p>

            @if (array_intersect(session('roles') ?? [], ['Student']))
            <button type="button" class="btn btn-secondary icon" id="btnEditProfile" data-bs-toggle="tooltip"
                data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Edit Profile Data">
                <i class="fa-solid fa-pencil"></i>
            </button>
            @endif

            <p id="headline"></p>
            <a id="uni-name" href="#" class="h6 fw-bold mb-0"></a>
            <p id="programme"></p>
            <button id="seeMoreActiveEducations" type="button" class="btn badge text-bg-primary"
                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip"
                data-bs-title="See More Active Educations">
                See More
            </button>
            <article>
                <p>
                    <i class="fa-solid fa-location-dot"></i>
                    <span id="profileLocation"></span>
                </p>
            </article>

            <x-links />

        </div>

        <div class="row g-2 profile-actions mt-lg-4 mt-md-4">
            @if (array_intersect(session('roles') ?? [], ['Student']))
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

            @elseif(array_intersect(session('roles') ?? [], ['Recruiter']))
            <div class="col-12 order-1 order-md-2">
                <button class="btn btn-outline-primary w-100 profile-action-btn" type="button">
                    <i class="fa-solid fa-share-nodes"></i>
                    Share
                </button>
            </div>
            @endif

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

@push('childScript')
<script>
    let stateProfile = {
        name: null,
        headline:null,
        programmes:null,
        location: null,
        email: null,
        phoneNo: null,
        profile_image: null,
        cover_image: null,
    }
</script>

<script type="module">

    async function renderUserDetail(name, headline, location, programmes, email, phoneNo, profile_image, cover_image){

        stateProfile.name = name
        stateProfile.headline = headline
        stateProfile.location = location
        stateProfile.programmes = programmes
        stateProfile.email = email
        stateProfile.phoneNo = phoneNo
        stateProfile.profile_image = profile_image
        stateProfile.cover_image = cover_image

        $("#name").text(stateProfile.name)
        $("#headline").text(stateProfile.headline)
        $("#profileLocation").text(stateProfile.location)
        $("#uni-name").text(stateProfile.programmes[0].organization.company_name)
        $("#programme").text(stateProfile.programmes[0].programme_name)

        let defaultProfile = `{{ asset(env('PROFILE_IMAGE_URL')) }}/default.png`;
        let profileUrl = `{{ asset(env('PROFILE_IMAGE_URL')) }}/${stateProfile.profile_image}`;
        let validProfileUrl = await xvalidate.getValidImageUrl(profileUrl, defaultProfile)
        $("#profileImage").css('background-image', `url("${validProfileUrl}")`);

        defaultProfile = `{{ asset(env('COVER_IMAGE_URL')) }}/default.png`;
        profileUrl = `{{ asset(env('COVER_IMAGE_URL')) }}/${stateProfile.cover_image}`;
        validProfileUrl = await xvalidate.getValidImageUrl(profileUrl, defaultProfile)
        $("#coverImage").css('background-image', `url("${validProfileUrl}")`);
    }

    $(document).on("profile:loaded", function (event, data) {
        renderUserDetail(
            data.name,
            data.headline,
            data.location,
            data.active_programmes,
            data.email,
            data.phone_no,
            data.profile_image,
            data.cover_image
        )
    })

    $(document).on("profile:stateProfile:updated", function (event, data) {
        renderUserDetail(
            data.name,
            data.headline,
            data.location,
            data.active_programmes ?? stateProfile.programmes,
            data.email,
            data.phone_no,
            data.profile_image,
            data.cover_image
        )
    })

    function handleSeeMoreEdu(){
        xmodal.show("activeEducationsModal")
        $("#activeEducationList").empty()

        if(stateProfile.programmes.length == 0){
            $("#activeEducationList").append(xeducation.student.emptyEducation())

        }else{
            stateProfile.programmes.forEach(programme => {
                $("#activeEducationList").append(xeducation.student.template(programme))
            });
        }
    }

    function handleProfileTab() {
        $(".profile-tab").removeClass("active");
        $(this).addClass("active");
        let target = $(this).data("target");
        // console.log(target);

        if (target === "main") {
            $("#mainTabContent").removeClass("d-none");
            $("#resultTabContent").addClass("d-none");
            $("#educationsTabContent").addClass("d-none");
        }

        if (target === "result") {
            $("#mainTabContent").addClass("d-none");
            $("#resultTabContent").removeClass("d-none");
            $("#educationsTabContent").addClass("d-none");
        }

        if (target === "education") {
            $("#mainTabContent").addClass("d-none");
            $("#resultTabContent").addClass("d-none");
            $("#educationsTabContent").removeClass("d-none");
            // handleLoadEducations()
        }
    }

    $(document).on('click', '.profile-tab', handleProfileTab);
    $(document).on("click", "#seeMoreActiveEducations", handleSeeMoreEdu)

</script>
@endpush
