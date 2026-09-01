import { formatDate } from "../utils/format.js"

export function recruitmentInvitationList(inv) {
    return `<div role="button" data-id=${inv.id} class="d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded"
                onclick="toggleFilter()">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary" style="width:4rem; border-radius: 50%; height:4rem; background-size: cover; background-image:url('${window.appConfig.profileImageUrl}/${inv.receiver.profile_image}')"></div>
                    <div class="flex-grow-1 d-flex flex-column">
                        <p class="fw-semibold">
                            ${inv.receiver.name}
                        </p>
                        <p>${inv.position.position_title}</p>
                        <div class="badge text-success border border-success w-75">
                            ${inv.invitation_status}
                        </div>
                        <small class="text-muted">
                        expires
                            ${formatDate(inv.expires_at)}
                        </small>
                    </div>
                </div>
                <i class="fa-solid fa-angle-right" style="color: rgb(0, 0, 0);"></i>
            </div>`
}
