<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content bg-dark">

            <div class="modal-header border-secondary">

                <h5 class="modal-title text-white" id="imagePreviewModalTitle">
                    Image Preview
                </h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body p-0">

                <div id="projectImageCarousel" class="carousel slide" data-bs-ride="false">

                    <div class="carousel-inner" id="imagePreviewCarouselInner">
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#projectImageCarousel"
                        data-bs-slide="prev">

                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#projectImageCarousel"
                        data-bs-slide="next">

                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">Next</span>
                    </button>

                </div>

            </div>

        </div>
    </div>

</div>

@push('scripts')
<script>
    function openImagePreview(images, selectedIndex = 0, modalTitle = "Image Preview") {
        console.log({ images, selectedIndex });

        const $carouselInner = $("#imagePreviewCarouselInner");

        $carouselInner.empty();

        $("#imagePreviewTitle").text(modalTitle);

        images.forEach((image, index) => {

            const active = index === selectedIndex ? "active" : "";

            $carouselInner.append(`
            <div class="carousel-item ${active}">
                <img
                    src="${image.url}"
                    class="d-block w-100"
                    alt="${image.title ?? `Image ${index + 1}`}"
                    style="
                        max-height: 80vh;
                        object-fit: contain;
                    "
                >

                ${image.title || image.description
                    ? `
                            <div class="carousel-caption d-block bg-dark bg-opacity-75 rounded p-2">
                                ${image.title
                        ? `<h5>${image.title}</h5>`
                        : ""
                    }

                                ${image.description
                        ? `<p class="d-none d-md-block mb-0">
                                            ${image.description}
                                           </p>`
                        : ""
                    }
                            </div>
                        `
                    : ""
                }
            </div>
        `);
        });

        const carouselEl = document.getElementById("imagePreviewCarousel");

        const carousel =
            bootstrap.Carousel.getOrCreateInstance(carouselEl, {
                interval: false
            });

        carousel.to(selectedIndex);

        bootstrap.Modal
            .getOrCreateInstance(
                document.getElementById("imagePreviewModal")
            )
            .show();
    }
</script>
@endpush.
