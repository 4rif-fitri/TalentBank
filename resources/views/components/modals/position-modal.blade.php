<div class="modal fade" id="shortlistModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="shortlistModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="shortlistModalLabel">
                    Add Positions
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

                <div class="modal-body">

                    <div class="row">

                        <input type="text" id="position_id" class="form-control" hidden>

                        <div class="col-md-12 mb-3">
                            <label for="position_title" class="form-label">
                                Position Title
                            </label>

                            <input type="text" name="position_title" id="position_title" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="employment_type" class="form-label">
                                Employment Type
                            </label>

                            <select name="employment_type" id="employment_type" class="form-select" required>

                                @foreach (\App\Constants\AppConstants::EMPLOYMENT_TYPES as $type)
                                <option value="{{ $type }}">
                                    {{ $type }}
                                </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="vacancies" class="form-label">
                                Vacancies
                            </label>

                            <input type="number" name="vacancies" id="vacancies" class="form-control" min="1"
                                placeholder="e.g. 5" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="department" class="form-label">
                                Department
                            </label>

                            <input type="text" name="department" id="department" class="form-control"
                                placeholder="e.g. Information Technology" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="work_location" class="form-label">
                                Work Location
                            </label>

                            <input type="text" name="work_location" id="work_location" class="form-control"
                                placeholder="e.g. Durian Tunggal, Melaka, Malaysia" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">
                                Description
                            </label>

                            <textarea name="description" id="description" class="form-control" rows="5"
                                placeholder="Enter employment description..." required></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" id="btnCloseModalPosition" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" id="btnAddShortlist" class="btn btn-primary">
                        Save
                    </button>
                    <button type="submit" id="btnUpdateShortlist" class="btn btn-primary">
                        Update
                    </button>

                </div>

        </div>
    </div>
</div>
<script type="module">
    window.shortlistModal = {
        myData: null,
        curruntPosition: null,

        init(data){
            this.myData = data
            console.warn(data);

            this.bindEvents()
        },

        async storePosition(position_title, employment_type, vacancies, department, work_location, description) {

            let data = {
                _token: $('meta[name="csrf-token"]').attr("content"),
                organization_id: this.myData.organization_users[0].organization_id,
                position_title: position_title,
                employment_type: employment_type,
                department: department,
                work_location: work_location,
                vacancies: vacancies,
                description: description
            }

            try {
                let response = await xApiPosition.store("{{ route('positions.store') }}", data)
                if(!response) return

                xalert.success("Success", response.message)
                this.close()

                // loadData()

            } catch (error) {
                console.error(error);
            }
        },

        open(){
            $("#listInvitationModal").hide()
            $("#btnUpdateShortlist").hide()
            xmodal.show("shortlistModal")
        },
        async handleEditPosition(){
            console.log(this.curruntPosition);

            xmodal.show("shortlistModal")
            let $form = $("#shortlistModal")
            $form.find("#position_id").val(this.curruntPosition.id)
            $form.find("#position_title").val(this.curruntPosition.position_title)
            $form.find("#employment_type").val(this.curruntPosition.employment_type)
            $form.find("#vacancies").val(this.curruntPosition.vacancies)
            $form.find("#department").val(this.curruntPosition.department)
            $form.find("#work_location").val(this.curruntPosition.work_location)
            $form.find("#description").val(this.curruntPosition.description)
            $("#btnAddShortlist").hide()
            $("#btnUpdateShortlist").show()
        },

        showModalUpdateShortlist() {
            $("#shortlistModal #position_id").val(curruntPosition.id)
            $("#shortlistModal #position_title").val(curruntPosition.position_title)
            $("#shortlistModal #employment_type").val(curruntPosition.employment_type)
            $("#shortlistModal #vacancies").val(curruntPosition.vacancies)
            $("#shortlistModal #department").val(curruntPosition.department)
            $("#shortlistModal #work_location").val(curruntPosition.work_location)
            $("#shortlistModal #description").val(curruntPosition.description)

            $("#btnAddShortlist").hide()
            $("#btnUpdateShortlist").show()
            xmodal.show("shortlistModal")
        },

        async updatePositions(position_id, position_title, employment_type, vacancies, department, work_location, description){
            let data = {
                organization_id: this.myData.organization_users[0].organization_id,
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
                let response = await xApiPosition.update("{{ route('positions.update', ['id' => '__ID__']) }}", position_id, data)
                if (!response) return
                let dt = response
                console.log(this.curruntPosition);

                this.curruntPosition = {
                    ...this.curruntPosition,
                    ...dt
                };

                console.log(this.curruntPosition);

                $(".ui_position_title").text(response.data.position_title)
                $(".ui_description").text(response.data.description)
                $(".ui_work_location").text(response.data.work_location)
                $(".ui_employment_type").text(response.data.employment_type)
                $(".ui_department").text(response.data.department)
                $(".ui_vacancies").text(response.data.vacancies)

                xalert.success("Success", response.message)
                this.close()
                this.handleCloseModalPosition()

            } catch (error) {
                console.error(error);
            }
        },

        async handleUpdateShortlist(e) {
            let position_id = $("#position_id").val()
            let position_title = $("#position_title").val()
            let employment_type = $("#employment_type").val()
            let vacancies = $("#vacancies").val()
            let department = $("#department").val()
            let work_location = $("#work_location").val()
            let description = $("#description").val()

            if (position_id == "") {
                xalert.fire("Validation Error", "Position id is NULL", "warning");
                return
            }

            if (position_title == "") {
                xalert.fire("Validation Error", "Please enter a position title", "warning");
                return
            }

            if (employment_type == "") {
                xalert.fire("Validation Error", "Please select employment type", "warning");
                return
            }

            if (vacancies == "") {
                xalert.fire("Validation Error", "Please enter a vacancies", "warning");
                return
            }

            if (department == "") {
                xalert.fire("Validation Error", "Please enter a department", "warning");
                return
            }

            if (work_location == "") {
                xalert.fire("Validation Error", "Please enter a work location", "warning");
                return
            }

            try {

                let response = await this.updatePositions(parseInt(position_id), position_title, employment_type, vacancies, department, work_location, description)
                if (!response) return

                xalert.fire('Success', response.message, 'success');
                form[0].reset();
                bootstrap.Modal.getOrCreateInstance($("#shortlistModal")).hide();

                shortListRender.detail(response.data)
                let $row = $("#shortlistList").find(`[data-id="${response.data.id}"]`)
                $row.find(".title").text(response.data.position_title)

            } catch (xhr) {
                console.error(xhr);
                xalert.fire("Error", xhr, "error")
            }
        },

        close(){
            xmodal.hide("shortlistModal")
        },

        async handleAddShortlist() {

            let form = $("#shortlistModal");

            let position_title = form.find("#position_title").val()
            let employment_type = form.find("#employment_type").val()
            let vacancies = form.find("#vacancies").val()
            let department = form.find("#department").val()
            let work_location = form.find("#work_location").val()
            let description = form.find("#description").val()

            if (position_title == "") {
                xalert.fire("Validation Error", "Please enter a position title", "warning");
                return
            }

            if (employment_type == "") {
                xalert.fire("Validation Error", "Please select employment type", "warning");
                return
            }

            if (vacancies == "") {
                xalert.fire("Validation Error", "Please enter a vacancies", "warning");
                return
            }

            if (department == "") {
                xalert.fire("Validation Error", "Please enter a department", "warning");
                return
            }

            if (work_location == "") {
                xalert.fire("Validation Error", "Please enter a work location", "warning");
                return
            }

            try {

                let response = await this.storePosition(position_title, employment_type, vacancies, department, work_location, description)

                if (!response) return

                xalert.fire('Success', response.message, 'success');
                form[0].reset();
                xmodal.hide("shortlistModal")

                xshortList.appendNew(response.data)

            } catch (xhr) {
                console.error(xhr);
                xalert.fire("Error", xhr, "error")
            }

        },

        handleCloseModalPosition() {
            xmodal.hide("shortlistModal")
            $("#shortlistModal").find("form")[0].reset();
        },

        bindEvents() {
            let self = this

            $(document).on("position:detail", function (event, details, candidateList) {
                self.curruntPosition = details
            });

            $(document).on("click", ".btnShowModalAddShortlist", function(){
                self.open()
            });

            $(document).on("click", "#btnAddShortlist", function(){
                self.handleAddShortlist()
            })

            $(document).on("click", ".btnShowModalUpdateShortlist", function(){
                self.handleEditPosition()
            })

            $(document).on("click", ".showModalUpdateShortlist", function(){
                self.showModalUpdateShortlist()
            });

            $(document).on("click", "#btnUpdateShortlist", function(){
                self.handleUpdateShortlist()
            })

            $(document).on("click", "#btnCloseModalPosition", function(){
                self.handleCloseModalPosition()
            })

        }
    }
</script>
