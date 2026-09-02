export function talentCard(data) {
    return `<div class="col-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card h-100 shadow-sm border-0 p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div></div>
                            <i class="fa-regular fa-heart text-muted" style="cursor:pointer;"></i>
                        </div>

                        <div class=" d-flex gap-2">
                            <div class="d-flex align-items-center gap-3 mb-3 mt-2">
                                <img src="${window.appConfig.baseURL}/uploads/profile-image-url/${data.profile_image}" class="rounded"
                                    width="60" alt="Profile">
                            </div>
                            <div class="mb-2" style="font-size: 12px;">
                                <h6 class="m-0 fw-bold">${data.name}</h6>
                                <p class="m-0 text-dark">Universiti Malaya</p>
                                <p class="m-0 text-muted">Bachelor of Computer Science</p>
                                <div class=" badge bg-primary">See More</div>
                            </div>
                        </div>

                        <div class="d-flex gap-1 flex-wrap">
                            <span class="badge bg-light text-dark border fw-normal">
                                <i class="fa-brands fa-youtube"></i>
                                React
                            </span>
                            <span class="badge bg-light text-dark border fw-normal">Laravel</span>
                            <span class="badge bg-light text-dark border fw-normal">Node.js</span>
                        </div>
                        <div class="mt-auto d-flex gap-2">
                            <a href="${window.appConfig.baseURL}/profile/student/${data.id}" target="_blank"
                                class="btn btn-sm btn-outline-primary w-50 fw-bold d-flex align-items-center justify-content-center">
                                View Profile
                            </a>
                            <button data-id=${data.id} class="btn btn-add-to-shortlist btn-sm btn-primary w-50 fw-bold">
                                Add to Shortlist
                            </button>
                        </div>
                    </div>
                </div>`
}
