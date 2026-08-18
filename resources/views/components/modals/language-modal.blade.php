<div class="modal fade" id="languageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content h-75">
            <div class="modal-header d-flex justify-content-between">
                <h1 class="modal-title fs-5 fw-bold" id="staticBackdropLabel">Languages</h1>
                <button type="button" class="btn btn-primary">Add Language</button>

            </div>
            <div class="modal-body" id="languageList">

                <!-- item -->
                <div class="alert alert-light d-flex align-items-center gap-3" role="alert">
                    <div class="d-flex flex-column flex-grow-1 gap-2 ">
                        <select class="form-select form-select-sm" aria-label="Small select example">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>

                        <select class="form-select form-select-sm" aria-label="Small select example">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="d-flex flex-column ratio-1x1">
                        <i class="fa-solid fa-circle-check fa-xl text-success" style="cursor: pointer;"></i>
                    </div>
                </div>

                <div class="alert alert-light d-flex align-items-center gap-3" role="alert">
                    <div class="d-flex flex-column flex-grow-1 gap-2 ">
                        <p class="h6 fw-bold">Bahasa Melayu</p>
                        <p>Native</p>
                    </div>
                    <div class="d-flex flex-column ratio-1x1">
                        <i class="fa-solid fa-pencil fa-lg" style="cursor: pointer;"></i>
                    </div>
                    <div class="d-flex flex-column ratio-1x1">
                        <i class="fa-solid fa-trash fa-lg text-danger" style="cursor: pointer;"></i>
                    </div>
                </div>
                <!-- item -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
