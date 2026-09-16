<aside class="shortlist-sidebar" id="listContainer">
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
        <h5 class="m-0 fw-bold">Your Positions</h5>
        <button type="button"
            class="btnShowModalAddShortlist btn btn-outline-primary d-flex justify-content-center align-items-center">
            <i class="fa-solid fa-plus fs-5"></i>
        </button>
    </div>

    <div id="shortlistList"></div>
</aside>

@push('childScript')
<script>
    window.positionList = {
        positions: null,

        init(datas){
            this.positions = datas
            this.render()
            this.bindEvents()
        },

        async handleClickShortlist(positionId) {

            try {
                $(".shortlist-item").each(function () {
                    $(this).removeClass("active");
                });

                $(this).addClass("active")

                let response = await xApiPosition.getPositionById("{{ route('positions.getPositionById', ['id' => '__ID__']) }}", positionId)
                if (!response) return

                let details = response.data
                let candidateList = response.data.shortlist_users

                $(document).trigger("position:detail",[details, candidateList])

            } catch (error) {
                console.error(error);
            }

        },

        bindEvents(){
            const self = this;

            $(document).on("click", "#listContainer .shortlist-item", function(){
                let positionId = $(this).data('id');
                self.handleClickShortlist(positionId)
            })
        },

        render(){
            const self = this;

            $("#shortlistList").empty();
            if (self.positions.length != 0) {
                self.positions.forEach(response => {
                    response.data.forEach(position => $("#shortlistList").append(xshortList.sideBar(position)));
                });
            } else {
                $("#shortlistList").append(`<small class="text-muted">No Data yet</small>`);
            }
        }
    }
</script>
@endpush
