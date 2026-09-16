
<aside class="filter-panel" id="filterPanel">
    <div class="d-flex justify-content-between align-items-center d-md-none mb-3 border-bottom pb-3">
        <h5 class="m-0 fw-bold">Filters</h5>
        <button class="btn btn-sm btn-light toggleFilter">
            <i class="fa-solid fa-xmark fs-5"></i>
        </button>
    </div>

    <div class="d-none d-md-flex justify-content-between align-items-center mb-4">
        <span class="fw-bold">Filters</span>
    </div>

    <div class="mb-3">
        <label class="form-label text-muted small fw-bold">Name</label>
        <input type="email" class="form-control" id="searchName" placeholder="Name">
    </div>

    <div class="mb-3">
        <label class="form-label text-muted small fw-bold">University</label>
        <select id="selectUniversiti" class="form-select select2-skills" multiple="multiple"
            data-placeholder="Select or type skills...">
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label text-muted small fw-bold">Skill</label>
        <select id="selectSkill" class="form-select select2-skills" multiple="multiple"
            data-placeholder="Select or type skills...">
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label text-muted small fw-bold">Language</label>
        <select id="selectLanguage" class="form-select select2-skills" multiple="multiple"
            data-placeholder="Select or type skills...">
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label text-muted small fw-bold">Qualifications</label>
        <select id="selectQualifications" class="form-select select2-skills" multiple="multiple"
            data-placeholder="Select or type skills...">
        </select>
    </div>

    <button class="btn btn-outline-danger w-100 mt-2" id="btnResetFilter">Reset</button>
    <button class="btn btn-primary w-100 mt-2" id="btnFilter">Filter</button>
</aside>

@push('childScript')

<script type="module">
    window.filterPanel = {

        init(isLike) {
            this.return_liked = isLike
            this.initSelect2();
            this.bindEvents();
        },

        initSelect2() {
            $('.select2-skills').select2({
                width: '100%',
                placeholder: function () {
                    return $(this).data('placeholder');
                },
                allowClear: true
            });
        },

        bindEvents() {
            const self = this;

            $(document).on('click', '#btnFilter', function () {
                $(document).trigger('filterPanel:filter', [self.getValues()])
            });

            $(document).on('click', '#btnResetFilter', function () {
                self.reset();
                $(document).trigger('filterPanel:reset');
            });
        },

        getValues() {
            return {
                organizations: $('#selectUniversiti').val() || [],
                skills: $('#selectSkill').val() || [],
                languages: $('#selectLanguage').val() || [],
                qualifications: $('#selectQualifications').val() || [],
                name: $('#searchName').val() || '' ,
            };
        },

        reset() {
            $('#searchName').val('');
            $('.select2-skills').val(null).trigger('change');
        },
    };
</script>
@endpush
