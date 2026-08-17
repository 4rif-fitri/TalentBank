<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark">

            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white">Project Highlights</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <div id="projectImageCarousel" class="carousel slide" data-bs-ride="false">
                    <div class="carousel-inner">

                        <div class="carousel-item active">
                            <img src="../assets/internship-assets/images/7.jpg" class="d-block w-100" alt="Preview 1">
                            <div class="carousel-caption d-block bg-dark bg-opacity-75 rounded p-2">
                                <h5>Title Image 1</h5>
                                <p class="d-none d-md-block">Description for image 1</p>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <img src="../assets/internship-assets/images/7.jpg" class="d-block w-100" alt="Preview 2">
                            <div class="carousel-caption d-block bg-dark bg-opacity-75 rounded p-2">
                                <h5>Title Image 2</h5>
                                <p class="d-none d-md-block">Description for image 2.</p>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <img src="../assets/internship-assets/images/7.jpg" class="d-block w-100" alt="Preview 3">
                            <div class="carousel-caption d-block bg-dark bg-opacity-75 rounded p-2">
                                <h5>Title Image 3</h5>
                                <p class="d-none d-md-block">Description for image 3.</p>
                            </div>
                        </div>

                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#projectImageCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#projectImageCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>

                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $(document).on("click", "[data-slide-index]", function () {
            let index = Number($(this).data("slide-index"));
            let carouselEl = document.getElementById("projectImageCarousel");
            let carousel = bootstrap.Carousel.getOrCreateInstance(carouselEl);
            carousel.to(index);
        });
    })
</script>
@endpush