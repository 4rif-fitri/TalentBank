<section id="myResume">
    <h3 class="fw-bold text-sm-center text-lg-start">My Resume</h3>
    <hr>


</section>


@push('childScript')

<script>

    function loadResumeData(id){
        let url = "{{ route('resumes.getResumeById', ['id' => '__ID__']) }}"
            .replace("__ID__", id)

        $.ajax({
            type: "GET",
            url,
            success: function (response) {
                console.log(response.data);

            }
        });
    }

    function loadResume(){
        let url = "{{ route('resumes.getResumesByUserProfileId', ['id' => '__ID__']) }}"
            .replace("__ID__", "{{ session('user_profile_id') }}")

        $.ajax({
            type: "GET",
            url,
            success: function (response) {
                let data = response.data[0]
                console.log(data);
                loadResumeData(data.id)
            }
        });
    }

    $(".profile-tab[data-target='myResume']").on("click", function () {
        console.log("myResume");
        loadResume()
    });


</script>
@endpush
