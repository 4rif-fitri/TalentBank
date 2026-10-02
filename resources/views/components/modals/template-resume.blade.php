<div class="modal fade" id="template-resume" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable ">
        <div class="modal-content h-75">

            <div class="modal-header d-flex justify-content-between">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Social Media Links</h1>
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

    listTemplate: [],

    load(){

    },

    selectTemplate(templateId) {
        window.location.href =
            `{{ route('student.edit.resume') }}?template_id=${templateId}`;
    },

    renderTemplateResume(listTemplate){
        let html = ''
        listTemplate.forEach((item, index) => {
            html += `
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">${item.template_file_name}</h5>
                            <p class="card-text">${item.thumbnail_file_name}</p>
                        </div>
                       <button class="btn btn-primary" onclick="templateResume.selectTemplate('${item.id}')">
                            Select
                        </button>
                    </div>
                </div>
            `
        })
        $('#list-template-resume').html(html)
    },

    open(listTemplate){
        this.renderTemplateResume(listTemplate)
        xmodal.show("template-resume")
    }
}

</script>

@endpush
