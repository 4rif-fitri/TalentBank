<section id="semesterResults">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="fw-bold">Semester Results</h3>
        <button class="btn btn-primary" id="addResult">
            <i class="fa-solid fa-plus"></i>
            Add Result
        </button>
    </div>
    <hr>

    <div id="semesterResultList">

    </div>

</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        $.ajax({
            url: "{{ route('programme.getProgrammesByUserIdJson',['userId' => auth()->id()]) }}",
            type: "GET",
            success: function (response) {
                console.log(response);

            },
            error: function (xhr) {
                Swal.fire({ title: "Upload Failed", text: xhr.responseJSON?.message ?? "Something went wrong", icon: "error" });

            }
        });
    })
</script>
@endpush