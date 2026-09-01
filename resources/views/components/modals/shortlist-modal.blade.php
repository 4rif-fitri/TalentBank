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

            <form id="shortlistForm">

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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" id="btnAddShortlist" class="btn btn-primary">
                        Save
                    </button>
                    <button type="submit" id="btnUpdateShortlist" class="btn btn-primary">
                        Update
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>