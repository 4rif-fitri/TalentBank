export function showModal(target_id){
    let modalElement = document.getElementById(target_id);
    let theModal = bootstrap.Modal .getOrCreateInstance(modalElement);
    theModal.show()
}

export function hideModalAlert() {
    $(document.activeElement).blur();
}

export function hideModal(target_id) {
    let modalElement = document.getElementById(target_id);
    let theModal = bootstrap.Modal .getOrCreateInstance(modalElement);
    theModal.hide()
}

export function show(target_id) {
    let modalElement = document.getElementById(target_id);
    let theModal = bootstrap.Modal.getOrCreateInstance(modalElement);
    theModal.show()
}

export function hide(target_id) {
    let modalElement = document.getElementById(target_id);
    let theModal = bootstrap.Modal.getOrCreateInstance(modalElement);
    theModal.hide()
}
