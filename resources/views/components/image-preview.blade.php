<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark">

            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white">Media Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <div id="imagePreviewCarousel" class="carousel slide" data-bs-ride="false">

                    <div class="carousel-inner" id="imagePreviewCarouselInner">
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#imagePreviewCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#imagePreviewCarousel"
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
    $(document).on("click", "[data-preview-image]", function () {
        let group = $(this).closest("[data-image-preview-group]");
        let images = group.find("[data-preview-image]");
        let selectedIndex = images.index(this);

        $("#imagePreviewCarouselInner").empty();

        images.each(function (index) {
            let image = $(this).data("image");
            let title = $(this).data("title") ?? "";
            let description = $(this).data("description") ?? "";

            $("#imagePreviewCarouselInner").append(`
				<div class="carousel-item ${index === selectedIndex ? "active" : ""}">
					<img src="${image}" class="d-block w-100"
						style="max-height: 75vh; object-fit: contain;">

					<div class="carousel-caption d-block bg-dark bg-opacity-75 rounded p-2">
						<h5>${title}</h5>
						<p class="d-none d-md-block">${description}</p>
					</div>
				</div>
			`);
        });

        let modal = bootstrap.Modal.getOrCreateInstance(
            document.getElementById("imagePreviewModal")
        );

        modal.show();
    });
</script>
@endpush
