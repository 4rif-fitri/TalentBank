<div class="results-panel flex-grow-1">

    <div class="row g-3" id="talent-cards"></div>

    <x-atom.pagination />

</div>

@push('childScript')

<script type="module">

    window.profilesPanel = {

        currentPage: 1,
        lastPage: 1,
        return_liked: false,
        filters: {},

        init(returnLiked = false) {
            this.return_liked = returnLiked;
            this.bindEvents();
            this.load();
        },

        bindEvents() {
            const self = this;

            $(document).on('filterPanel:filter', function (event, filters) {
                self.filters = filters;
                self.currentPage = 1;
                self.load();
            });

            $(document).on('filterPanel:reset', function () {
                self.filters = {};
                self.currentPage = 1;
                self.load();
            });

            $(document).on('pagination:change', function (event, page) {
                self.goToPage(page);
            });
        },

        load() {

            $.ajax({
                url: "{{ route('profile.getAllStudentUserProfiles') }}",
                type: "GET",
                data: {
                    ...this.filters,
                    page: this.currentPage,
                     return_liked: this.return_liked
                },

                success: (response) => {
                    const data = response.data;
                    this.currentPage = data.current_page;
                    this.lastPage = data.last_page;
                    this.render(data);
                },

                error: function (xhr) {
                    console.error(xhr.responseJSON?.message);
                }
            });
        },

        goToPage(page) {
            if (page < 1 || page > this.lastPage || page === this.currentPage) return;
            this.currentPage = page;
            this.load();
        },

        render(data) {

            $('#total-found').text(`${data.total} students found`);

            $('#talent-cards').empty();

            data.data.forEach(student => {
                $('#talent-cards').append(xprofile.student.talentCard(student));
            });

            pagination.render({
                total: data.total,
                currentPage: data.current_page,
                lastPage: data.last_page
            });
        },

        reset() {
            this.currentPage = 1;
            this.lastPage = 1;
            this.filters = {};
            $('#talent-cards').empty();
            this.renderPagination();
        }
    };

</script>

@endpush
