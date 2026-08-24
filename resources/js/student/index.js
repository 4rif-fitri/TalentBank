import { initProfile } from './profile/index.js';

$(document).ready(function () {
    initProfile();

    $("[data-bs-toggle='tooltip']").each(function () {
        new bootstrap.Tooltip(this);
    });
});
