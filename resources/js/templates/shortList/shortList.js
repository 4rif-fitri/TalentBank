import { formatDate } from "../../utils/format"

export function sideBar(position) {
    return `<div data-id=${position.id} class="shortlist-item d-flex justify-content-start align-items-center gap-3 mb-2 p-3 border rounded" role="button">
                <div class="d-flex align-items-center gap-3">
                    <i class="fa-regular fa-folder fa-xl"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold title">
                            ${position.position_title}
                        </div>
                        <small class="text-muted">
                            Created ${formatDate(position.created_at)}
                        </small>
                    </div>
                </div>
            </div>`
}

export function detail(data) {
    return `<div data-id=${data.id} class="card h-100 shadow-sm border-0 p-3 position-relative">
                <div class="dropdown position-absolute top-0 end-0 m-3">
                    <button type="button" class="btn btn-sm p-1" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                            <button data-id=${data.id} class="dropdown-item btnShowModalUpdateShortlist">
                                    <i class="fa-regular fa-copy me-2"></i>
                                    Edit
                            </button>
                        </li>
                        <li>
                            <button data-id=${data.id} class="dropdown-item text-danger" id="btnDeleteShortlist">
                                <i class="fa-regular fa-trash-can me-2"></i>
                                Delete
                            </button>
                        </li>
                    </ul>
                </div>
                <h1 class="fw-bolder">${data.position_title}</h1>
                <p class="mt-1">${data.description}</p>
                <small class="text-muted">Location: ${data.work_location}</small>
                <div class="d-flex mt-2 gap-2">
                    <div class="badge text-dark border">${data.employment_type}</div>
                    <div class="badge text-dark border">${data.department}</div>
                    <div class="badge text-dark border">${data.vacancies} person</div>
                </div>
                <hr>

                    <table id="tableDetail" class="table table-bordered text-center">
                        <thead>
                            <tr class="table-primary">
                                <th scope="col">Candidate</th>
                                <th scope="col" class="d-none d-md-block border-0">Skills</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>`
}

export function candidate(user) {
    return `<tr data-id=${user.id}>
                <td>
                    <div class="d-flex gap-2">
                        <h6 class="text-start">${user.name}</h6>
                        <!-- <p class="text-muted text-start">(UTEM)</p> -->
                    </div>
                </td>
                <td class="d-none d-md-block">
                    <div class="badge border text-black">
                        <i class="fa-brands fa-laravel fa-lg"></i>
                        laravel
                    </div>
                    <div class="badge border text-black">
                        <i class="fa-brands fa-laravel fa-lg"></i>
                        laravel
                    </div>
                    <div class="badge border text-black">
                        <i class="fa-brands fa-laravel fa-lg"></i>
                        laravel
                    </div>
                    <div class="badge border text-black">
                        <i class="fa-brands fa-plus "></i>
                        5
                    </div>
                </td>
                <td>
                    <div class="badge bg-primary">Added</div>
                </td>
                <td class="position-relative">
                    <button type="button" class="btn btn-light border" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
                    <div class="dropdown position-absolute top-0 end-0">
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li>
                                <a target="_blank" href="${window.appConfig.baseURL}/profile/student/${user.id}" class="dropdown-item">
                                    <i class="fa-solid fa-address-book"></i>
                                    View Profile
                                </a>
                            </li>
                            <!-- <li>
                                <button data-id=${user.id} class="dropdown-item text-danger">
                                    <i class="fa-solid fa-circle-minus"></i>
                                    Cencel Invite
                                </button>
                            </li> -->

                            <li>
                                <button data-id=${user.id} class="dropdown-item btnShowModalAddInvite">
                                    <i class="fa-regular fa-trash-can me-2"></i>
                                    Invite
                                </button>
                            </li>

                            <li>
                                <button data-id=${user.id} class="dropdown-item btnShowModalAddinterview">
                                    <i class="fa-regular fa-trash-can me-2"></i>
                                    Set Interview
                                </button>
                            </li>


                            <li>
                                <button data-id=${user.id} class="dropdown-item text-danger" id="btnDeletTalentRow">
                                    <i class="fa-regular fa-trash-can me-2"></i>
                                    Delete
                                </button>
                            </li>

                        </ul>
                    </div>
                </td>
            </tr>`
}
