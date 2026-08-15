<div class="modal fade" id="activeEducationsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable ">
        <div class="modal-content h-50">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Active Educations</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="activeEducationList">



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')

<script>
    $(document).ready(function () {
        $("#seeMoreActiveEducations").on("click", function () {
            let activeEducationsModalEl = document.getElementById('activeEducationsModal');
            let activeEducationsModal = bootstrap.Modal.getOrCreateInstance(activeEducationsModalEl);
            activeEducationsModal.show()
        })
    })
</script>

@endpush
