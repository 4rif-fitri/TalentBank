export function formatDate(date) {
    return new Date(date).toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
}

export function formatDateFull(date) {
    return new Date(date).toLocaleString('en-GB', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });
}

export function formatTime(date) {
    return new Date(date).toLocaleTimeString('en-GB', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });
}

export function formatDateShort(date) {
    return new Date(date).toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}

export function setEducationDate(date, monthSelector, yearSelector) {
    if (!date) {
        $(monthSelector).val("");
        $(yearSelector).val("");
        return;
    }

    let [year, month, day] = date.split("-");

    $(yearSelector).val(year);

    if (month === "01" && day === "01") {
        $(monthSelector).val("");
    } else {
        $(monthSelector).val(month);
    }
}

export function buildValidDate(month, year) {
    if (!year) return null;
    if (!month) return `${year}-01-01`;
    return `${year}-${month}-01`;
}
export function formatDateTime(date) {
    return new Date(date).toLocaleDateString("en-GB", {
        day: "numeric",
        month: "long",
        year: "numeric",
        hour: "numeric",
        minute: "2-digit",
        hour12: true
    });
}
