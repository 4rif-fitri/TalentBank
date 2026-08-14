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
    // Tab nav
    $(".profile-tab").on("click", function () {
        $(".profile-tab").removeClass("active");
        $(this).addClass("active");

        let target = $(this).data("target");
        if (target === "main") {
            $("#mainTabContent").removeClass("d-none");
            $("#resultTabContent").addClass("d-none");

        } else if (target === "result") {
            $("#mainTabContent").addClass("d-none");
            $("#resultTabContent").removeClass("d-none");
            if (!resultLoaded) loadSemesterResults()
        }
    });
    // Tab nav

    let email

    function getProfileData() {
        $.ajax({
            url: "{{ route('profile.getProfileDataByUserIdJson', ['userId' => auth()->id()]) }}",
            type: "GET",
            dataType: "json",
            success: function ({ data }) {
                console.log(data);
                $("#name").text(data.name)
                $("#headline").text(data.headline)
                $("#aboutText").text(data.about)
                $("#profileLocation").text(data.location)
                $("#programme").text(data.active_programme.programme_name)

                let imageUrl = "{{ asset('cover-image-url') }}/" + data.cover_image;
                $("#coverImage").css("background-image", `url("${imageUrl}")`);
                imageUrl = "{{ asset('profile-image-url') }}/" + data.profile_image;
                $("#profileImage").css("background-image", `url("${imageUrl}")`);

                email = data.email
            },
            error: function (xhr) {
                console.error(xhr);
            }
        });
    }
    getProfileData()

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


    $('#profileImageInput').on('change', function () {
        let file = this.files[0];

        if (fileCheck(file)) return

        let formData = new FormData();
        formData.append('profile_image', file);

        $.ajax({
            url: "{{ route('update.uploadProfileImage') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                getProfileData()
                Swal.fire({ title: "Success", text: "Cover image uploaded successfully", icon: "success" });
                getProfileData()
            },
            error: function (xhr) {
                Swal.fire({ title: "Upload Failed", text: xhr.responseJSON?.message ?? "Something went wrong", icon: "error" });
            }
        });
    });

    $('#coverImageInput').on('change', function () {
        let file = this.files[0];

        if (fileCheck(file)) return

        let formData = new FormData();
        formData.append('cover_image', file);

        $.ajax({
            url: "{{ route('update.uploadCoverImage') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                getProfileData()
                Swal.fire({ title: "Success", text: "Cover image uploaded successfully", icon: "success" });
                getProfileData()
            },
            error: function (xhr) {
                Swal.fire({ title: "Upload Failed", text: xhr.responseJSON?.message ?? "Something went wrong", icon: "error" });
            }
        });
    });

</script>
@endsection
