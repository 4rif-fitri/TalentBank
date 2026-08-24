// handle... / show...
import {
    saveProfile,saveProfileImage,
    saveCoverImage,saveAbout,
    createLink,deleteLink,
    updateLink,addLanguage,
    deleteLanguage, updateLanguage
} from './service.js';

import {
    templateSocialMediaRow,
    templateBadgeSocialMedia,
    templateEditSocialMedia,

} from '../../templates/socialMedia/template.js';

import {
    renderCoverImages,renderProfileImages,
    renderBasicProfile,renderContactProfile,
    renderAbout,renderAddLink,
    renderLanguageOptions,renderProficiencyOptions
} from './renderer.js';

import {templateLanguagesAddRow,templateLanguagesRow,
        templateLanguages,templatelanguageOptions,
        templateProficiencyOptions,templateLanguagesUpdateRow } from "../../templates/languageTemplate.js"

import { salert } from '../../utils/alert.js';
import {showModal,hideModal} from '../../utils/modal.js';
import { profileState, languages } from './state.js';
import { validateLenghtText, checkFormData, isValidUrl } from "../../utils/validation.js"

// ==== GET ====
function getProfileFormData() {
    let formData = new FormData();
    formData.append('name', $('#profileNameInput').val());
    formData.append('location', $('#locationInput').val());
    formData.append('headline', $('#profileHeadlineInput').val());
    formData.append('email', $('#contactEmailInput').val());
    formData.append('phone_no', $('#contactPhoneNoInput').val());
    formData.append('_method', 'PUT');

    return formData;
}
// ==== GET ====

// ==== SHOW ====
export function showProfileEditModal() {

    let profile = profileState.data;

    if (!profile) {
        console.error('Profile data not available');
        return;
    }

    showModal('editProfileModal');

    $('#profileNameInput').val(profile.name ?? '');
    $('#locationInput').val(profile.location ?? '');
    $('#profileHeadlineInput').val(profile.headline ?? '');
    $('#profileEmailInput').val(profile.email ?? '');
    $('#profilePhoneNoInput').val(profile.phone_no ?? '');
}

export function showActiveEducationModal() {
    showModal('activeEducationsModal');
}

export function showAboutModal() {
    let profile = profileState.data;

    if (!profile) {
        console.error('Profile data not available');
        return;
    }
    $("#aboutInput").val(profile.about ?? '');
    $("#aboutCount").text($("#aboutInput").val().length);
    showModal('editAboutModal');
}

export function showContactModal() {
    let profile = profileState.data;

    if (!profile) {
        console.error('Profile data not available');
        return;
    }

    $("#contactEmailInput").val(profile.email)
    $("#contactPhoneNoInput").val(profile.phone_no)

    showModal('editContactInformationModal');
}

export function showLinksSocialMediaModal(){
    let profile = profileState.data;

    if (!profile) {
        console.error('Profile data not available');
        return;
    }

    showModal('socialMediaModal');
}

export async function showLanguageMediaModal() {
    showModal('languageModal');
}

export async function showSkillModal() {
    showModal('skillModal');
}
// ==== SHOW ====

// ==== HANDLE ====
function handleProfileValidationError(error) {

    if (error?.responseJSON?.errors) {
        let errors = error.responseJSON.errors;
        console.error('Validation errors:', errors);
        return;
    }

    console.error('Unexpected profile error:', error);
}

export async function handleUploadProfileImage(event) {

    try {
        let response = await saveProfileImage(event);
        if (!response) return

        renderProfileImages(response.data);
        salert('Success','Profile image uploaded successfully','success');

    } catch (error) {
        console.error('Failed to upload profile image:',error);
    }
}

export async function handleUploadCoverImage(event) {
    try {
        let response = await saveCoverImage(event);
        if (!response) return;

        renderCoverImages(response.data);
        salert('Success','Cover image uploaded successfully','success');

    } catch (xhr) {
        salert("Error", xhr.responseJSON.message, "error")
        console.error('Failed to upload cover image:', xhr);
    }
}

export async function handleProfileEditSubmit() {
    let formData = getProfileFormData();

    try {
        let response = await saveProfile(formData);
        if (!response) return;

        //akan overwrite data yang perlu je
        profileState.data = {
            ...profileState.data,
            ...response.data
        };

        renderBasicProfile(profileState.data);
        hideModal('editProfileModal');
        salert('Success','Profile updated successfully','success');

    } catch (error) {
        console.error('Failed to update profile:',error);
        handleProfileValidationError(error);
    }
}

export function handleInputAbout(event){
    console.log($(this).val().length);

    if (validateLenghtText($(this).val().length, 255)){
        return
    }
    $("#aboutCount").text(this.value.length);
}

export async function handleUpdateAbout(){
    let formData = new FormData();
    formData.append("about", $("#aboutInput").val())
    formData.append('_method', 'PUT');

    checkFormData(formData)

    try {
        let response = await saveAbout(formData);
        if (!response) return;

        //akan overwrite data yang perlu je
        profileState.data = {
            ...profileState.data,
            ...response.data
        };

        renderAbout(profileState.data);
        hideModal('editAboutModal');
        salert('Success', 'About updated successfully', 'success');

    } catch (error) {
        console.error('Failed to update About:', error);
    }
}

export async function handleUpdateContact() {
    let formData = getProfileFormData();

    try {
        let response = await saveProfile(formData);
        if (!response) return;

        //akan overwrite data yang perlu je
        profileState.data = {
            ...profileState.data,
            ...response.data
        };

        renderContactProfile(profileState.data);
        hideModal('editContactInformationModal');
        salert('Success', 'contact updated successfully', 'success');

    } catch (error) {
        console.error('Failed to update profile:', error);
        handleProfileValidationError(error);
    }
}

export function handleAddRowSocialMediaLink(){
    renderAddLink()
}

export function handleCencelAddLink(){
    $(this).parent().remove()
}

export async function handleAddLink(){
    let $row = $(this).closest(".social-media-row");
    let $dropdown = $row.find(".dropdown-toggle");

    let socialMediaId = $row.find(".dropdown-toggle").attr("data-id");
    let link = $row.find('input[type="url"]').val().trim();

    if (!socialMediaId) {
        salert("Validation Error","Please select a social media platform.","warning");
        return;
    }

    if (!isValidUrl(link)) {
        salert("Validation Error","Please enter a valid URL.","warning");
        return;
    }

    let formData = new FormData();
    formData.append("social_media_id", socialMediaId);
    formData.append("link", link);

    let platformName = $dropdown.text().trim();
    let platformIcon = $dropdown.find("i").attr("class");

    try {
        let response = await createLink(formData);
        if (!response) return;
        console.log(response);
        let linkId = response.data?.id;

        $row.replaceWith(
            templateSocialMediaRow(linkId, socialMediaId,platformName,platformIcon,link)
        );

        let theLink = {
            link,
            social_media:{
                icon_class_name: platformIcon,
                name:platformName
            }
        }
        $("#linksList").append(templateBadgeSocialMedia(theLink))

        salert("Success",response.message ?? "Social media link added successfully.","success");

    } catch (xhr) {
        salert("Add Failed",xhr.responseJSON?.message ?? "Something went wrong.","error");
    }
}

export function handleDeleteLink() {
    let $row = $(this).closest(".social-media-row");
    let id = $row.data("id");

    console.log({ $row ,id});
    Swal.fire({
        title: "Delete Link?",
        text: "This social media link will be permanently deleted.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
        confirmButtonColor: "#dc3545"

    }).then( async result => {

        if (!result.isConfirmed) {
            return;
        }
        try {
            let response = await deleteLink(id);
            if (!response) return;
            // console.log(response);
            let theBadge = $("#linksList").find(`.badge[data-id='${id}']`);
            theBadge.remove();
            $row.remove();

            salert('Success', 'contact updated successfully', 'success');
        } catch (xhr) {
            salert("Delete Failed",xhr.responseJSON?.message ?? "Something went wrong.","error");
        }
    });
}

export function handleSelectSocialMedia(){
    const id = $(this).data("id");
    const name = $(this).data("name");
    const icon = $(this).data("icon");

    const $inputGroup = $(this).closest(".input-group");
    const $dropdownButton = $inputGroup.find(".dropdown-toggle");

    $dropdownButton.attr("data-id", id)
        .html(`<i class="${icon} me-1"></i>${name}`);
}

export function handleEditLink(){
    let $row = $(this).closest(".social-media-row");
    let id = $row.data("id");
    let socialMediaId = $row.data("social-media-id");

    let currentName = $row.find(".social-media-name").text().trim();
    let currentLink = $row.find(".social-media-link").attr("href");

    $row.html(templateEditSocialMedia(socialMediaId, currentName, currentLink))
}

export async function handleUpdateLink(){
    let $row = $(this).closest(".social-media-row");
    let id = $row.data("id");

    let socialMediaId = $row.find(".dropdown-toggle").attr("data-id");

    let link = $row.find('input[type="url"]').val().trim();
    $row.attr("data-social-media-id", socialMediaId)
        .data("social-media-id", Number(socialMediaId));

    if (!socialMediaId) {
        salert("Validation Error","Please select social media.","warning");
        return;
    }
    if (!isValidUrl(link)) {
        salert("Validation Error","Please enter a valid URL.","warning");
        return;
    }

    let formData = new FormData();
    formData.append("social_media_id", socialMediaId);
    formData.append("link", link);
    formData.append("_method", "PUT");

    try {
        let response = await updateLink(id, formData);
        if (!response) return;
        let data = response.data;

        // Guna .html() untuk "replace" semua isi dalam badge
        let theBadge = $("#linksList").find(`.badge[data-id='${data.id}']`);
        theBadge.html(`<i class="${data.social_media.icon_class_name}"></i> ${data.social_media.name}`);

        $row.parent().prepend(templateSocialMediaRow(data.id, data.social_media.id, data.social_media.name, data.social_media.icon_class_name, data.link));
        $row.remove();

        salert(
            "Success",
            response.message ??
            "Social media link updated successfully.",
            "success"
        );

    } catch (xhr) {
        console.error(xhr);

        salert(
            "Update Failed",
            xhr.responseJSON?.message ??
            "Something went wrong.",
            "error"
        );
    }
}

export function handleAddLanguage(){
    let languageOptions = renderLanguageOptions()
    let proficiencieOptions = renderProficiencyOptions()

    $("#userLanguageList").prepend(templateLanguagesAddRow(languageOptions, proficiencieOptions))

}

export async function handleSaveAddLanguage() {
    let $row = $(this).closest(".language-row");
    let languageId = $row.find(".language-select").val();
    let languageName = $row.find(".language-select option:selected").text();
    let proficiency = $row.find(".language-proficiency").val();

    // console.log({languageId,languageName,proficiency});

    if (!languageId) {
        salert("Validation Error", "Please select a language.", "error");
        return;
    }

    if (!proficiency) {
        salert("Validation Error", "Please select proficiency.", "error");
        return;
    }

    try {
        let response = await addLanguage(languageId, proficiency);
        response.data.language = {
            language_name: languageName
        };

        $("#languageList").append(templateLanguages(response.data))
        $row.replaceWith(templateLanguagesRow(response.data));

        salert("Success", response.message ?? "Language added successfully.", "success");
    } catch (xhr) {
        salert("Create Failed", xhr.responseJSON?.message ?? "Something went wrong.", "error");
    }
}

export  function handleDeleteLanguage(){
    let $row = $(this).closest(".language-row");
    let id = $row.data("id");

    if (!id) {
        $row.remove();
        return;
    }

    Swal.fire({
        title: "Delete Language?",
        text: "This language will be removed.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Delete",
        confirmButtonColor: "#dc3545"

    }).then(async result => {

        if (!result.isConfirmed) return;

        try {
            let response = await deleteLanguage(id);
            if (!response) return;
            console.log(response);
            let theBadge = $("#languageList").find(`.language-item[data-id='${id}']`);
            theBadge.remove();
            $row.remove();

            salert("Success", response.message ?? "Language deleted successfully.", "success");
        } catch (xhr) {
            salert("Delete Failed", xhr.responseJSON?.message ?? "Something went wrong.", "error");
        }
    });
}

export function handleEditLanguage(){
    let $row = $(this).closest(".language-row");
    let id = $row.data("id");
    let languageId = $row.data("language-id");
    let theProficiency = $row.data("proficiency");

    let languagesOptions = ""
    languages.data.forEach(language => languagesOptions += templatelanguageOptions(language, languageId))
    console.log(languagesOptions);

    let proficiencyOptions = ""
    let proficiencies = window.appConfig.proficiencies
    proficiencies.forEach(proficiency => proficiencyOptions += templateProficiencyOptions(proficiency, theProficiency))
    console.log(proficiencyOptions);

    $row.replaceWith(
        templateLanguagesUpdateRow(languagesOptions, proficiencyOptions, id)
    );
}

export async function handleUpdateLanguage() {
    let $row = $(this).closest(".language-row");
    let id = $row.data("id");
    let languageId = $row.find(".language-select").val();
    let proficiency = $row.find(".language-proficiency").val();

    console.log({id,languageId,proficiency});

    if (!languageId) {
        salert("Validation Error","Please select a language.","error");
        return;
    }

    if (!proficiency) {
        salert("Validation Error","Please select proficiency.","error");
        return;
    }

    try {
        let response = await updateLanguage(id,parseInt(languageId),proficiency);
        if (!response) return;
        console.log(response);

        let data = response.data;
        $row.replaceWith(templateLanguagesRow(data));

        let languageRow = $("#languageList").find(`.language-item[data-id="${data.id}"]`);
        languageRow.find(".languageName").text(data.language.language_name)
        languageRow.find(".languageProficiency").text(data.proficiency_level)

        salert("Success",response.message ?? "Language updated successfully.","success");

    } catch (xhr) {
        console.error(xhr);
        salert("Update Failed",xhr.responseJSON?.message ?? "Something went wrong.","error");
    }
}

// ==== HANDLE ====




