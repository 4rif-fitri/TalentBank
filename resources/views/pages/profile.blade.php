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

    </div>


    <div class="user-card-right">

        <x-sections.contact-information />

        <x-sections.languages />

        <x-sections.skills />

    </div>

</div>


<x-offcanvas />

<x-modals.imagePreviewModal />

<x-modals.editAboutModal />

<x-modals.editProfileModal />

<x-modals.addResultModal />

<x-modals.edit-contact-information-modal />

<x-modals.pdf-preview-modal />

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
        text: @json($errors -> first()),
        icon: "error"
    });
</script>

@endif


<script>

    // ==========================================
    // GET PROFILE
    // ==========================================

    function getProfileData() {

        $.ajax({

            url: "{{ route(
                'profile.getProfileDataByUserIdJson',
            ['userId' => auth() -> id()]
            )
    }}",

    type: "GET",

        dataType: "json",

            success: function ({ data }) {

                $("#name")
                    .text(data.name ?? "");

                $("#headline")
                    .text(data.headline ?? "");

                $("#aboutText")
                    .text(data.about ?? "");

                $("#profileLocation")
                    .text(data.location ?? "");


                const programme =
                    data.active_programmes?.[0];


                if (programme) {

                    $("#programme")
                        .text(programme.programme_name ?? "")
                        .show();


                    $("#uni-name")
                        .text(programme.organization_name ?? "")
                        .show();

                } else {

                    $("#programme, #uni-name")
                        .hide();

                }


                // Cover
                const coverImageUrl =
                    "{{ asset('cover-image-url') }}/"
                    + data.cover_image;


                $("#coverImage")
                    .css(
                        "background-image",
                        `url("${coverImageUrl}")`
                    );


                // Profile
                const profileImageUrl =
                    "{{ asset('profile-image-url') }}/"
                    + data.profile_image;


                $("#profileImage, #profileBtn")
                    .css(
                        "background-image",
                        `url("${profileImageUrl}")`
                    );

            },


    error: function (xhr) {

        console.error(xhr);

    }

        });

    }



    // ==========================================
    // FILE CHECK
    // ==========================================

    function fileCheck(file) {

        if (!file) {

            Swal.fire({
                title: "Upload Failed",
                text: "Please select an image",
                icon: "error"
            });

            return true;

        }

        return false;

    }



    // ==========================================
    // PROFILE IMAGE
    // ==========================================

    $("#profileImageInput")
        .on("change", function () {

            const file =
                this.files[0];


            if (fileCheck(file)) {
                return;
            }


            const formData =
                new FormData();


            formData.append(
                "profile_image",
                file
            );


            $.ajax({

                url: "{{ route('update.uploadProfileImage') }}",

                type: "POST",

                data: formData,

                processData: false,

                contentType: false,

                headers: {

                    "X-CSRF-TOKEN":
                        $('meta[name="csrf-token"]')
                            .attr("content")

                },


                success: function () {

                    getProfileData();


                    Swal.fire({
                        title: "Success",
                        text: "Profile image uploaded successfully",
                        icon: "success"
                    });

                },


                error: function (xhr) {

                    Swal.fire({
                        title: "Upload Failed",
                        text:
                            xhr.responseJSON?.message ??
                            "Something went wrong",
                        icon: "error"
                    });

                }

            });

        });



    // ==========================================
    // COVER IMAGE
    // ==========================================

    $("#coverImageInput")
        .on("change", function () {

            const file =
                this.files[0];


            if (fileCheck(file)) {
                return;
            }


            const formData =
                new FormData();


            formData.append(
                "cover_image",
                file
            );


            $.ajax({

                url: "{{ route('update.uploadCoverImage') }}",

                type: "POST",

                data: formData,

                processData: false,

                contentType: false,

                headers: {

                    "X-CSRF-TOKEN":
                        $('meta[name="csrf-token"]')
                            .attr("content")

                },


                success: function () {

                    getProfileData();


                    Swal.fire({
                        title: "Success",
                        text: "Cover image uploaded successfully",
                        icon: "success"
                    });

                },


                error: function (xhr) {

                    Swal.fire({
                        title: "Upload Failed",
                        text:
                            xhr.responseJSON?.message ??
                            "Something went wrong",
                        icon: "error"
                    });

                }

            });

        });



    // ==========================================
    // PROFILE TABS
    // ==========================================

    $(".profile-tab")
        .on("click", function () {

            $(".profile-tab")
                .removeClass("active");


            $(this)
                .addClass("active");


            const target =
                $(this).data("target");


            if (target === "main") {

                $("#mainTabContent")
                    .removeClass("d-none");

                $("#resultTabContent")
                    .addClass("d-none");

            }


            if (target === "result") {

                $("#mainTabContent")
                    .addClass("d-none");

                $("#resultTabContent")
                    .removeClass("d-none");

                // Jangan request API kat sini.
                // Semester component handle sendiri.

            }

        });



    // ==========================================
    // INITIAL
    // ==========================================

    getProfileData();

</script>

@endsection
