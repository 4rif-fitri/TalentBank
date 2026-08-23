// get... save... update... delete... create... upload...
// getProfile()         saveProfile()       updateProfile()     deleteProfile()
// saveProfileImage()   saveCoverImage()
// createEducation()    updateEducation()   deleteEducation()

import {
    updateProfile,
    uploadProfileImage,
    uploadCoverImage,
    updateAbout
} from '../../api/profile.js';

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
