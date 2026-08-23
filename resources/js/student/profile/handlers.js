// handle... / show...
import {
    saveProfile,
    saveProfileImage,
    saveCoverImage
} from './service.js';

import {
    renderCoverImages,
    renderProfileImages,
    renderBasicProfile
} from './renderer.js';

import { alertSuccess } from '../../utils/alert.js';
import {showModal,hideModal} from '../../utils/modal.js';
import { profileState } from './state.js';


export async function handleUploadProfileImage(event) {

    try {
        const response = await saveProfileImage(event);
        if (!response) return

        renderProfileImages(response.data);
        alertSuccess('Success','Profile image uploaded successfully','success');

    } catch (error) {
        console.error('Failed to upload profile image:',error);
    }
}

export async function handleUploadCoverImage(event) {
    try {
        const response = await saveCoverImage(event);
        if (!response) return;

        renderCoverImages(response.data);
        alertSuccess('Success','Cover image uploaded successfully','success');

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
        alertSuccess('Success','Profile updated successfully','success');

    } catch (error) {
        console.error('Failed to update profile:',error);
        handleProfileValidationError(error);
    }
}


function getProfileFormData() {
    const formData = new FormData();
    formData.append('name',$('#profileNameInput').val());
    formData.append('location',$('#locationInput').val());
    formData.append('headline',$('#profileHeadlineInput').val());
    formData.append('email',$('#profileEmailInput').val());
    formData.append('phone_no',$('#profilePhoneNoInput').val());
    formData.append('_method','PUT');

    return formData;
}

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

function handleProfileValidationError(error) {

    if (error?.responseJSON?.errors) {
        const errors = error.responseJSON.errors;
        console.error('Validation errors:', errors);
        return;
    }

    console.error('Unexpected profile error:',error);
}
