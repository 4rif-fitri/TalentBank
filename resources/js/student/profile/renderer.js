// render + UI target
// renderBasicProfile()
// renderProfileImages()

// <=== Template ===>
import {
    templateSocialMediaRow,
    templateBadgeSocialMedia,
    templatAddLink
} from '../../templates/profile/socialMediaTemplate.js'
import {
    templateLanguages,
    templateLanguagesRow,
    templatelanguageOptions,
    templateProficiencyOptions
} from "../../templates/profile/languageTemplate.js"
import {
    templateRowSkillList,
    templateRowSkillModal,
    templateSkillOption
} from "../../templates/profile/skillTemplate.js"
import{
    templateLoadingEducation,
    templateEmptyEducation
}from "../../templates/profile/educationTemplate.js"
// <=== Template ===>

import { profileState, languages, skills } from './state.js';
import { educationTemplate } from '../../templates/profile/educationTemplate.js'
import { getValidImageUrl } from "../../utils/validation.js"

export function renderProfile() {
    let data = profileState.data;
    if (!data) return

    renderBasicProfile(data);
    renderAbout(data);
    renderContactProfile(data)
    renderProfileImages(data);
    renderCoverImages(data)
    renderLanguages(data.user_languages ?? [])
    renderSkills(data.skills ?? [])
    renderActiveEducation(data.active_programmes ?? []);
    renderModalActiveEducations(data.active_programmes ?? []);
    renderListLinkSocialMedia(data.social_media_links ?? [])
}

export function renderAbout(data){
    $("#aboutText").text(data.about ?? "");
}

export async function renderCoverImages(data) {
    let defaultCover = `${window.appConfig.assets.coverImage}/default.png`;
    let coverUrl = `${window.appConfig.coverImageUrl}/${data.cover_image || 'default.png'}`;
    let validCoverUrl = await getValidImageUrl(coverUrl,defaultCover);
    $('#coverImage').css('background-image',`url("${validCoverUrl}")`);

}

export async function renderProfileImages(data) {
    let defaultProfile = `${window.appConfig.assets.profileImage}/default.png`;
    let profileUrl = `${window.appConfig.profileImageUrl}/${data.profile_image || 'default.png'}`;
    let validProfileUrl = await getValidImageUrl(profileUrl,defaultProfile);
    $('#profileImage').css('background-image',`url("${validProfileUrl}")`);
}

export function renderBasicProfile(data) {
    $("#name").text(data.name ?? "");
    $("#headline").text(data.headline ?? "");
    $("#profileLocation").text(data.location ?? "");

    $("#profileNameInput").val(data.name ?? "");
    $("#locationInput").val(data.headline ?? "");
    $("#profileHeadlineInput").val(data.location ?? "");
}

export function renderContactProfile(data) {
    $("#email").text(data.email ?? "");
    $("#phoneNo").text(data.phone_no ?? "");

    $("#contactEmailInput").val(data.email ?? "");
    $("#contactPhoneNoInput").val(data.phone_no ?? "");
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
        $container.append(educationTemplate(programme));
    });
}

export function renderListLinkSocialMedia(links){
    $("#linksList").empty();
    $("#socialMediaList").empty()

    links.forEach(link => {
        $("#linksList").append(templateBadgeSocialMedia(link))
    })

    links.forEach(link => {
        $("#socialMediaList").append(templateSocialMediaRow(link.id, link.social_media.id, link.social_media.name, link.social_media.icon_class_name,link.link))
    })

}

export function renderAddLink(){
    $("#socialMediaList").prepend(templatAddLink())
}

export function renderLanguages(languages){
    $("#languageList").empty()
    $("#userLanguageList").empty()

    languages.forEach(language => {
        $("#languageList").append(templateLanguages(language));
    });

    languages.forEach(language => {
        $("#userLanguageList").append(templateLanguagesRow(language));
    });
}

export function renderLanguageOptions(){
    let html = "<option value='' selected disable>Select Language</option>"
    languages.data.forEach(language => html += templatelanguageOptions(language))
    return html
}

export function renderProficiencyOptions() {
    let html = "<option value='' selected disable>Select Proficiency</option>"
    let proficiencies = window.appConfig.proficiencies
    proficiencies.forEach(proficiency => html += templateProficiencyOptions(proficiency));
    return html
}

export function renderSkills(skills){
    $("#skillList").empty();
    $("#skillListModal").empty();

    skills.forEach(skill => {
        $("#skillList").append(templateRowSkillList(skill))
    })

    skills.forEach(skill => {
        $("#skillListModal").append(templateRowSkillModal(skill))
    })
}

export function renderSkillOptions() {
    let options = ""
    let skill = skills.data
    skill.forEach(skill => {
        options += templateSkillOption(skill)
    })
    return options
}

export function renderLoading(){
    $("#educationIndicators").hide();
    $("#educationList").html(templateLoadingEducation())
}

export function renderEmptyCardEducation(){
    $("#educationIndicators").hide();
    $("#educationList").html(templateEmptyEducation())
}

export function renderBadgeEducationSkill() {

}
function getEducationItemsPerSlide() {
    return $("#educationCarousel").width() < 1000 ? 1 : 2;
}
export function renderCardEducation(data){
    $("#educationList").empty();
    let itemsPerSlide = getEducationItemsPerSlide();
            let totalSlides = Math.ceil(data.length / itemsPerSlide);


}
