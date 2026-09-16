<div class="shortlist-content flex-grow-1">
    <div class="row g-3" id="shortlistContent">
        <div class="card shadow-sm border-0 p-3 d-flex justify-content-center align-items-center ">
            <i class="fa-regular fa-folder-open" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
            <h4 class="mt-2">No Position Selected Yet</h4>
            <button class="btn btn-primary d-block d-lg-none btn-toggle-filter toggleFilter">
                <i class="fa-solid fa-filter"></i>
                Positions
            </button>
        </div>
    </div>
</div>

@push('childScript')
<script>
    window.positionDetail = {
        details: null,
        candidateList: null,

        init() {
            this.bindEvents()
        },

        render(){
            xshortList.detail(this.details)
        },

        bindEvents(){
            const self = this;

            $(document).on("position:detail", function(event, details, candidateList){
                self.details = details
                self.candidateList = candidateList
                self.render()
            })
        }
    }
</script>
@endpush
