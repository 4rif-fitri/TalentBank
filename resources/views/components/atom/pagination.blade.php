<div class="mt-2 d-flex justify-content-between align-items-center" id="pagination">
    <span class="fw-bold" data-pagination-total></span>

    <nav aria-label="Pagination">
        <ul class="pagination" data-pagination-list></ul>
    </nav>
</div>

@push('childScript')
<script type="module">

    window.pagination = {

        element: null,
        totalElement: null,
        listElement: null,

        init() {
            this.element = $('#pagination');
            this.totalElement = this.element.find('[data-pagination-total]');
            this.listElement = this.element.find('[data-pagination-list]');
            this.bindEvents();
        },
        bindEvents() {

            this.listElement.on('click', '.page-target', function () {
                const page = $(this).data('page');
                if (!page) return;

                $(document).trigger('pagination:change',[page]);
            });

        },
        render(data) {
            this.totalElement.text(`${data.total} students found`);
            this.listElement.empty();
            this.renderPrevious(data);
            this.renderPages(data);
            this.renderNext(data);
        },

        renderPrevious(data) {
            const disabled = data.currentPage === 1;

            this.listElement.append(`
                <li class="page-target page-item ${disabled ? 'disabled' : ''}"
                    data-page="${disabled ? '' : data.currentPage - 1}">
                    <a class="page-link">
                        Previous
                    </a>
                </li>`);
        },

        renderPages(data) {

            for (let page = 1; page <= data.lastPage; page++) {

                this.listElement.append(`
                    <li class="page-target page-item ${page === data.currentPage ? 'active' : '' }" data-page="${page}" >
                        <a class="page-link">
                            ${page}
                        </a>
                    </li>`);
            }
        },

        renderNext(data) {
            const disabled = data.currentPage === data.lastPage;

            this.listElement.append(`
                <li class="page-target page-item ${disabled ? 'disabled' : ''}"
                    data-page="${disabled ? '' : data.currentPage + 1}">
                    <a class="page-link">
                        Next
                    </a>
                </li>`);
        },

        reset() {
            this.totalElement.text('');
            this.listElement.empty();
        }
    };

</script>
@endpush
