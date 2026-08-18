<div class="modal fade" id="socialMediaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content h-75">
            <div class="modal-header d-flex justify-content-between">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Social Media Links</h1>
                <button type="button" class="btn btn-primary">Add Link</button>
            </div>
            <div class="modal-body" id="socialMediaList">

                <!-- item -->
                <div class="alert alert-light d-flex align-items-center gap-3" role="alert">
                    <div class="d-flex flex-column flex-grow-1 gap-1 ">
                        <div class="dropdown">
                            <button class="btn btn-sm w-100 text-start" type="button">
                                <i class="fa-brands fa-github fa-lg"></i>
                                Github
                                <a href="">https://getbootstrap.com/docs</a>
                            </button>
                        </div>
                    </div>
                    <div class="d-flex flex-column ratio-1x1">
                        <i class="fa-solid fa-pencil fa-lg" style="cursor: pointer;"></i>
                    </div>
                    <div class="d-flex flex-column ratio-1x1">
                        <i class="fa-solid fa-floppy-disk fa-lg text-danger" style="cursor: pointer;"></i>
                    </div>
                </div>

                <div class="alert alert-light d-flex align-items-center gap-2" role="alert">

                    <div class="input-group flex-grow-1">

                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-brands fa-github me-1"></i>
                            Github
                        </button>

                        <ul class="dropdown-menu">
                            <li>
                                <button class="dropdown-item" type="button">
                                    <i class="fa-brands fa-github me-2"></i>
                                    Github
                                </button>
                            </li>

                            <li>
                                <button class="dropdown-item" type="button">
                                    <i class="fa-brands fa-linkedin me-2"></i>
                                    LinkedIn
                                </button>
                            </li>
                        </ul>

                        <input type="url" class="form-control" placeholder="https://">

                    </div>

                    <div class="d-flex align-items-center justify-content-center px-2">
                        <i class="fa-solid floppy-disk fa-xl text-success" style="cursor: pointer;"></i>
                    </div>

                </div>


                <!-- item -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
