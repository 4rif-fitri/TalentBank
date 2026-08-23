export function initProfileEvents() {
    $(document).on('click','#seeMoreActiveEducations',showActiveEducationModal);
}

function showActiveEducationModal() {
    $('#activeEducationModal').modal('show');
}
