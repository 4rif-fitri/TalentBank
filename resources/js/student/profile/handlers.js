// handle... / show...
import {
    saveProfile,
    saveProfileImage,
    saveCoverImage,
    saveAbout
} from './service.js';

import {
    renderCoverImages,
    renderProfileImages,
    renderBasicProfile,
    renderContactProfile
} from './renderer.js';

import { salert } from '../../utils/alert.js';
import {showModal,hideModal} from '../../utils/modal.js';
import { profileState } from './state.js';
import { validateLenghtText, checkFormData } from "../../utils/validation.js"

// ==== GET ====
function getProfileFormData() {
    const formData = new FormData();
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

    const profile = profileState.data;

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
    const profile = profileState.data;

    if (!profile) {
        console.error('Profile data not available');
        return;
    }
    $("#aboutInput").val(profile.about ?? '');
    $("#aboutCount").text($("#aboutInput").val().length);
    showModal('editAboutModal');
}

export function showContactModal() {
    const profile = profileState.data;

    if (!profile) {
        console.error('Profile data not available');
        return;
    }

    $("#contactEmailInput").val(profile.email)
    $("#contactPhoneNoInput").val(profile.phone_no)

    showModal('editContactInformationModal');
}
// ==== SHOW ====

// ==== HANDLE ====
function handleProfileValidationError(error) {

    if (error?.responseJSON?.errors) {
        const errors = error.responseJSON.errors;
        console.error('Validation errors:', errors);
        return;
    }

    console.error('Unexpected profile error:', error);
}

export async function handleUploadProfileImage(event) {

    try {
        const response = await saveProfileImage(event);
        if (!response) return

        renderProfileImages(response.data);
        salert('Success','Profile image uploaded successfully','success');

    } catch (error) {
        console.error('Failed to upload profile image:',error);
    }
}

export async function handleUploadCoverImage(event) {
    try {
        const response = await saveCoverImage(event);
        if (!response) return;

        renderCoverImages(response.data);
        salert('Success','Cover image uploaded successfully','success');

    } catch (error) {
        console.error('Failed to upload cover image:',error);
    }
}

export async function handleProfileEditSubmit() {
    const formData = getProfileFormData();

    try {
        const response = await saveProfile(formData);
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
        const response = await saveAbout(formData);
        if (!response) return;

        //akan overwrite data yang perlu je
        profileState.data = {
            ...profileState.data,
            ...response.data
        };

        renderBasicProfile(profileState.data);
        hideModal('editAboutModal');
        salert('Success', 'About updated successfully', 'success');

    } catch (error) {
        console.error('Failed to update About:', error);
    }
}

export async function handleUpdateContact() {
    const formData = getProfileFormData();

    try {
        const response = await saveProfile(formData);
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

// ==== HANDLE ====




