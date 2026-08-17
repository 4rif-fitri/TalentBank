<div class="offcanvas m-auto" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel">
    <div class="offcanvas-header">
        <h3 class="offcanvas-title fw-bold" id="offcanvasBottomLabel">Add Section</h3>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body small">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Core
                    </button>
                </h2>

                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="list-group">
                        <a href="./education.html" class="list-group-item list-group-item-action">Education</a>
                        <a href="./project.html" class="list-group-item list-group-item-action">Project</a>
                        <a href="./experience.html" class="list-group-item list-group-item-action">Experience</a>
                        <a href="./honors&awards.html" class="list-group-item list-group-item-action">Honors &
                            Awards</a>
                        <a href="./certifications.html"
                            class="list-group-item list-group-item-action">Certifications</a>
                    </div>
                </div>

            </div>


            <div class="accordion-item">

                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Recommended
                    </button>
                </h2>


                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="list-group">
                        <a href="./languages.html" class="list-group-item list-group-item-action">
                            Languages
                        </a>
                        <a href="./skills.html" class="list-group-item list-group-item-action">
                            Skills
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>


@push('scripts')
    <script>
        function adjustOffcanvasPosition() {

            let offcanvasEl = document.getElementById("offcanvasBottom");
            if (window.innerWidth >= 768) {

                offcanvasEl.classList.remove("offcanvas-bottom");
                offcanvasEl.classList.add("offcanvas-end");
                offcanvasEl.style.height = "100vh";
                offcanvasEl.style.width = "400px";

            } else {

                offcanvasEl.classList.remove("offcanvas-end");
                offcanvasEl.classList.add("offcanvas-bottom");
                offcanvasEl.style.height = "60vh";
                offcanvasEl.style.width = "100%";

            }

        }
        adjustOffcanvasPosition();
        window.addEventListener("resize", adjustOffcanvasPosition);
    </script>
@endpush
