// get... save... update... delete... create... upload...
// getProfile()         saveProfile()       updateProfile()     deleteProfile()
// saveProfileImage()   saveCoverImage()
// createEducation()    updateEducation()   deleteEducation()

import {
    updateProfile,
    uploadProfileImage,
    uploadCoverImage,
    updateAbout,
} from '../../api/profile.js';

import {
    store,
    remove
} from '../../api/socialMedia.js';

import { uploadImage } from '../../utils/upload.js';

export function saveProfile(formData) {
    return updateProfile(formData);
}

export function saveProfileImage(event) {
    return uploadImage(event,'profile_image',uploadProfileImage);
}

export function saveCoverImage(event) {
    return uploadImage(event,'cover_image',uploadCoverImage);
}

export function saveAbout(formData) {
    return updateAbout(formData)
}

export function createLink(formData) {
    return store(formData)
}

export function deleteLink(id) {
    return remove(id)
}
