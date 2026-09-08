export function salert(title, text, icon) {
    Swal.fire({ title, text, icon });
}
export function alert(title, text, icon) {
    Swal.fire({ title, text, icon });
}
export function success(title, text) {
    Swal.fire({ title, text, icon: "success" });
}
export function error(title, text) {
    Swal.fire({ title, text, icon: "error" });
}
