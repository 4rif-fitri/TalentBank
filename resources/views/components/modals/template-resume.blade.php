<div class="modal fade" id="template-resume" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable ">
        <div class="modal-content h-75">

            <div class="modal-header d-flex justify-content-between">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Social Media Links</h1>
                <button type="button" class="btn btn-primary" id="addlink">Add Link</button>
            </div>

            <div class="modal-body" id="list-template-resume"></div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="btnHideSocialMediaModal">Close</button>
            </div>
        </div>
    </div>
</div>
@push('childScript')

<script type="module">

window.templateResume = {
     open(){
        xmodal.show("template-resume")
     }
}

</script>

@endpush
