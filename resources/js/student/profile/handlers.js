// handle... / show...
import {
    saveProfile,
    saveProfileImage,
    saveCoverImage,
    saveAbout,
    createLink

} from './service.js';

import {
    templateSocialMediaRow,
    templateBadgeSocialMedia
} from '../../templates/socialMedia/template.js';

import {
    renderCoverImages,
    renderProfileImages,
    renderBasicProfile,
    renderContactProfile,
    renderAbout,
    renderAddLink
} from './renderer.js';

import { salert } from '../../utils/alert.js';
import {showModal,hideModal} from '../../utils/modal.js';
import { profileState } from './state.js';
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

    } catch (error) {
        console.error('Failed to upload cover image:',error);
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

        //akan overwrite data yang perlu je
        // profileState.data = {
        //     ...profileState.data,
        //     ...response.data
        // };

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

export function handleSelectSocialMedia(){
    const id = $(this).data("id");
    const name = $(this).data("name");
    const icon = $(this).data("icon");

    const $inputGroup = $(this).closest(".input-group");
    const $dropdownButton = $inputGroup.find(".dropdown-toggle");

    $dropdownButton.attr("data-id", id)
        .html(`<i class="${icon} me-1"></i>${name}`);
}

// ==== HANDLE ====




