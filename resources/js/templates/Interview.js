import { formatDateFull } from "../utils/format.js"

export function sidebar(interview) {
    return `<div data-id=${interview.id} class="d-flex justify-content-between align-items-center gap-3 shortlist-item mb-2 p-3 border rounded"
                    onclick="toggleFilter()">
                    <div role="button" class="d-flex align-items-center gap-3">
                    <div class="bg-primary" style="width:4rem; border-radius: 50%; height:4rem; background-size: cover; background-image:url('${window.appConfig.profileImageUrl}/${interview.invitation.receiver.profile_image}')"></div>
                        <div class="flex-grow-1 d-flex flex-column">
                            <p class="fw-semibold">${interview.invitation.receiver.name}</p>
                            <p>${interview.invitation.position.position_title}</p>
                            <smoll>${formatDateFull(interview.scheduled_at)}</smoll>
                        </div>
                    </div>
                </div>`
}
