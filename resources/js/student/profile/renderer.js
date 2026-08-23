import { profileState } from './state.js';
import { activeEducationsAlert } from '../../templates/education/activeEducationsAlert.js'
import { getValidImageUrl } from "../../utils/validation.js"

export function renderProfile() {
    let data = profileState.data;

    if (!data) return

    renderBasicProfile(data);
    renderProfileImages(data);
    renderActiveEducation(data.active_programmes ?? []);
    renderModalActiveEducations(data.active_programmes ?? []);
}

export async function renderProfileImages(data) {

    let defaultCover = `${window.appConfig.assets.coverImage}/default.png`;
    let defaultProfile = `${window.appConfig.assets.profileImage}/default.png`;
    let coverUrl = `${window.appConfig.assets.coverImage}/${data.cover_image || 'default.png'}`;
    let profileUrl = `${window.appConfig.assets.profileImage}/${data.profile_image || 'default.png'}`;

    let validCoverUrl = await getValidImageUrl(coverUrl,defaultCover);
    let validProfileUrl = await getValidImageUrl(profileUrl,defaultProfile);

    $('#coverImage').css('background-image',`url("${validCoverUrl}")`);
    $('#profileImage').css('background-image',`url("${validProfileUrl}")`);
}

export function renderBasicProfile(data) {
    $("#name").text(data.name ?? "");
    $("#headline").text(data.headline ?? "");
    $("#aboutText").text(data.about ?? "");
    $("#profileLocation").text(data.location ?? "");
}

export function renderActiveEducation(programmes) {

    if (programmes.length > 0) {
        let programme = programmes[0];
        $('#uni-name').text(programme.organization?.company_name ?? '').show();
        $('#programme').text(programme.programme_name ?? '').show();

    } else {
        $('#programme, #uni-name, #seeMoreActiveEducations').hide();

    }
}

export function renderModalActiveEducations(programmes) {

    let $container = $('#activeEducationList');

    $container.empty();

    programmes.forEach(programme => {
        $container.append(activeEducationsAlert(programme));
    });
}
