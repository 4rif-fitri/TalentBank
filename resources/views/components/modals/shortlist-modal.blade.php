<div class="modal fade" id="shortlistModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="shortlistModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">

                <h5 class="fw-semibold modal-title" id="shortlistModalLabel">
                    Add to Shortlist
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <form id="shortlistForm">

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12 mb-3">

                            <label for="candidateName" class="form-label">
                                Candidate Name
                            </label>

                            <input type="hidden" id="candidateId">

                            <input type="text" id="candidateName" class="form-control" readonly required>

                        </div>

                        <div class="col-12">

                            <label for="selectPosition" class="form-label">
                                Position
                            </label>

                            <select id="selectPosition" class="form-select" required>
                                <option value="">
                                    Select Position
                                </option>
                            </select>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" id="btnCloseModal" class="btn btn-secondary">
                        Cancel
                    </button>

                    <button type="submit" id="btnAddShortlist" class="btn btn-primary">
                        Save
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

@push('childScript')


<script type="module">

    function getShortlistedPositionIds(profileId, orgId) {
        let url = "{{ route('shortlists.getShortlistedPositionIds', ['profileId' => '__profileId__','orgId' => '__orgId__'])}}";
        url = url.replace("__profileId__", profileId);
        url = url.replace("__orgId__", orgId);

        return $.ajax({
            url: url,
            type: "GET"
        });
    }

    window.shortlistModal = {
        candidate: null,
        organizationWithPosition: [],

        init(organizationWithPosition) {
            this.organizationWithPosition = organizationWithPosition;
        },

        async open(candidate) {

            try {
                this.candidate = candidate;
                this.setCandidate(candidate);
                this.resetPositions();

                xmodal.show("shortlistModal");
                await this.loadPositions();

            } catch (error) {

                console.error("Failed to open shortlist modal:",error);
            }
        },


        setCandidate(candidate) {
            $("#candidateId").val(candidate.id);
            $("#candidateName").val(candidate.name);
        },

        resetPositions() {
            $("#selectPosition").html(`<option value="">Select Position</option>`);
        },

        async loadPositions() {

            const candidateId = this.candidate.id;

            const results = await Promise.all(

                this.organizationWithPosition.map(
                    async data => {

                        const organizationId = data.org.organization.id;
                        const response =
                            await getShortlistedPositionIds(candidateId,organizationId);

                        return {
                            ...data,
                            shortlistedPositionIds: response.data
                        };

                    }
                )

            );

            this.renderPositions(results);
        },

        renderPositions(results) {

            let html = `<option value="">Select Position</option>`;

            results.forEach(data => {
                html += `<optgroup label="${data.org.organization.company_name}">`;

                data.position.forEach(pos => {
                    const isAdded = data.shortlistedPositionIds.includes(pos.id);

                    html += `<option
                                value="${pos.id}"
                                ${isAdded ? "disabled" : ""}>
                                ${pos.position_title}
                                ${isAdded ? "(Added)" : ""}
                            </option>`;
                });

                html += `</optgroup>`;
            });
            $("#selectPosition").html(html);
        },


        async save() {

            const candidateId =
                this.candidate?.id;

            const positionId =
                $("#selectPosition").val();


            if (!candidateId) {

                xalert.alert(
                    "Error",
                    "Candidate not found.",
                    "error"
                );

                return;

            }


            if (!positionId) {

                xalert.alert(
                    "Error",
                    "Please select a position.",
                    "error"
                );

                return;

            }


            try {

                const response = await $.ajax({

                    url: "{{ route('shortlists.store') }}",

                    type: "POST",

                    data: {

                        user_profile_id:
                            candidateId,

                        position_id:
                            positionId,

                        _token:
                            $('meta[name="csrf-token"]').attr(
                                "content"
                            )

                    }

                });


                xalert.alert(
                    "Success",
                    response.message,
                    "success"
                );

                this.close();

            } catch (xhr) {
                console.error(xhr);
                xalert.error(xhr.responseJSON?.message ?? "Something went wrong.","error");
            }

        },

        close() {
            xmodal.hide("shortlistModal");
            this.reset();
        },

        reset() {
            this.candidate = null;

            $("#shortlistForm")[0].reset();
            $("#candidateId").val("");
            $("#candidateName").val("");

            this.resetPositions();
        }
    };
</script>

@endpush
