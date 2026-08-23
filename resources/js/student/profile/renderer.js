import { profileState } from './state.js';
import { activeEducationsAlert } from '../../templates/education/activeEducationsAlert.js'

export function renderProfile() {
    const data = profileState.data;

    if (!data) return

    renderBasicProfile(data);
    renderProfileImages(data);
    renderActiveEducation(data.active_programmes ?? []);
    renderModalActiveEducations(data.active_programmes ?? []);
}

export function renderProfileImages(data) {

    if (data.cover_image) {
        const coverImageUrl = `${window.appConfig.assets.coverImage}/${data.cover_image}`;
        $('#coverImage').css('background-image',`url("${coverImageUrl}")`);
    }

    if (data.profile_image) {
        const profileImageUrl = `${window.appConfig.assets.profileImage}/${data.profile_image}`;
        $('#profileImage, #profileBtn').css('background-image',`url("${profileImageUrl}")`);
    }
}

export function renderBasicProfile(data) {
    $("#name").text(data.name ?? "");
    $("#headline").text(data.headline ?? "");
    $("#aboutText").text(data.about ?? "");
    $("#profileLocation").text(data.location ?? "");
}

export function renderActiveEducation(programmes) {

    if (programmes.length > 0) {
        const programme = programmes[0];
        $('#uni-name').text(programme.organization?.company_name ?? '').show();
        $('#programme').text(programme.programme_name ?? '').show();

    } else {
        $('#programme, #uni-name, #seeMoreActiveEducations').hide();

    }
}

export function renderModalActiveEducations(programmes) {

    const $container = $('#activeEducationList');

    $container.empty();

    programmes.forEach(programme => {
        $container.append(activeEducationsAlert(programme));
    });
}
