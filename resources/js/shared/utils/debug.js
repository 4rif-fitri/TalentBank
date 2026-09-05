export function log(label, data) {
    console.group(label);
    console.log(data);
    console.groupEnd("")
}

export function error(label, data) {
    console.error(label, data);
}

export function table(label, data) {
    console.table(label, data);
}
