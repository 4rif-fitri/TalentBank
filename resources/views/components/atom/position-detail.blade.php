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
        async updatePositions(id, position_title, employment_type, vacancies, department, work_location, description) {
            let data = {
                organization_id: myData.organization_users[0].organization_id,
                position_title: position_title,
                employment_type: employment_type,
                department: department,
                work_location: work_location,
                vacancies: vacancies,
                description: description,
                _method: "PUT",
                _token: $('meta[name="csrf-token"]').attr("content")
            }

            try {
                let response = await xApiPosition.update("{{ route('positions.update', ['id' => '__ID__']) }}", id, data)
                if (!response) return
                console.log("response", response);
                $(".ui_position_title").text(response.data.position_title)
                $(".ui_description").text(response.data.description)
                $(".ui_work_location").text(response.data.work_location)
                $(".ui_employment_type").text(response.data.employment_type)
                $(".ui_department").text(response.data.department)
                $(".ui_vacancies").text(response.data.vacancies)

                xalert.success("Success", response.message)

                handleCloseModalPosition()

            } catch (error) {
                console.error(error);
            }
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
