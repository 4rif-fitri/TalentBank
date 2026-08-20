@extends('layouts.internship-layouts')

@section('content')

<div class="user-card">

    <div class="user-card-left">

        <x-sections.userCardHeader />

        <div id="mainTabContent">
            <x-sections.about />
        </div>

        <div id="resultTabContent" class="d-none">
            <x-sections.semesterResults />
        </div>

        <div id="educationsTabContent" class="d-none">
            <x-sections.education />
        </div>

    </div>


    <div class="user-card-right">

        <x-sections.contact-information />

        <x-sections.languages />

        <x-sections.skills />

    </div>

</div>


<x-offcanvas />

<x-modals.semester-modal />

<x-modals.imagePreviewModal />

<x-modals.active-educations-modal />

<x-modals.editAboutModal />

<x-modals.editProfileModal />

<x-modals.addResultModal />

<x-modals.edit-contact-information-modal />

<x-modals.pdf-preview-modal />

<x-modals.social-media-link-modal />

<x-modals.languages-modal />

<x-modals.skill-modal />

@endsection

@section('script')
<script>
    let profileData = null;
    function renderLanguages(userLanguages = []) {
        let $container = $("#languageList");

        $container.empty();

        if (!userLanguages.length) {
            $container.html(`
            <p class="text-secondary mb-0">
                No languages added.
            </p>
        `);

            return;
        }

        userLanguages.forEach(item => {
            $container.append(`
            <article class="language-item mb-2"
                     data-id="${item.id}">

                <p class="fw-bold mb-0">
                    ${item.language?.language_name ?? ""}
                </p>

                <p class="text-secondary mb-0">
                    ${item.proficiency_level ?? ""}
                </p>

            </article>
        `);
        });
    }

    function getProfileData() {

        let url = "{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", "{{ session('user_profile_id') }}");

        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            success: function ({ data }) {
                profileData = data

                console.log("DATA: ", data);
                $("#name").text(data.name ?? "");
                $("#headline").text(data.headline ?? "");
                $("#aboutText").text(data.about ?? "");
                $("#profileLocation").text(data.location ?? "");

                let programme = data.active_programmes?.[0];

                if (programme) {
                    $("#uni-name").text(data.active_programmes[0].organization.company_name ?? "").show();
                    $("#programme").text(data.active_programmes[0].programme_name ?? "").show();
                } else {
                    $("#programme, #uni-name, #seeMoreActiveEducations").hide();
                }

                let coverImageUrl = "{{ asset('cover-image-url') }}/" + data.cover_image;
                $("#coverImage").css("background-image", `url("${coverImageUrl}")`);

                let profileImageUrl = "{{ asset('profile-image-url') }}/" + data.profile_image;
                $("#profileImage, #profileBtn").css("background-image", `url("${profileImageUrl}")`);

                data.active_programmes.forEach(data => {

                    let $element = $("<div>").addClass("alert alert-primary d-flex gap-2").attr("role", "button");
                    $element.html(`
                        <div class="bg-body d-flex justify-content-center align-items-center"
                            style="width: 40px; height: 40px; border-radius: 50%;">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>

                        <div>
                            <p>${data.organization.company_name ?? "Unknown Institution"}</p>
                            <p>${data.programme_name ?? ""}</p>
                            <p>${data.programme_level ?? ""}</p>
                            <p>${data.duration_years ?? ""} Years</p>
                        </div>`);

                    $("#activeEducationList").append($element);
                });

                getAllSocialMedia()
                renderSocialMediaLinks(data.social_media_links);
                renderLanguages(data.user_languages ?? []);
            },

            error: function (xhr) {
                console.error(xhr);
            }
        });
    }

    function fileCheck(file) {
        if (!file) {
            swalfire("Upload Failed", "Please select an image", "error")
            return true;
        }
        return false;
    }

    $("#profileImageInput").on("change", function () {
        let file = this.files[0];

        if (fileCheck(file)) return;

        let formData = new FormData();
        formData.append("profile_image", file);

        $.ajax({
            url: "{{ route('update.uploadProfileImage') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function () {
                getProfileData();
                swalfire("Success", "Profile image uploaded successfully", "success")
            },

            error: function (xhr) {
                swalfire("Upload Failed", xhr.responseJSON?.message ?? "Something went wrong", "error")
            }
        });
    });

    $("#coverImageInput").on("change", function () {
        let file = this.files[0];

        if (fileCheck(file)) return;
        let formData = new FormData();
        formData.append("cover_image", file);

        $.ajax({
            url: "{{ route('update.uploadCoverImage') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            success: function () {
                getProfileData();
                swalfire("Success", "Cover image uploaded successfully", "success")
            },
            error: function (xhr) {
                swalfire("Upload Failed", xhr.responseJSON?.message ?? "Something went wrong", "error")
            }
        });
    });

    $(".profile-tab").on("click", function () {
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
        }
    });

    window.swalfire = function (title, text, icon) {
        Swal.fire({ title: title, text: text, icon: icon });
    };

    $(document).ready(function () {
        getProfileData();

        $("[data-bs-toggle='tooltip']").each(function () {
            new bootstrap.Tooltip(this);
        });
    })
</script>

@endsection