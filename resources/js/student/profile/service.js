import {
    updateProfile,
    uploadProfileImage,
    uploadCoverImage
} from '../../api/profile.js';

import { uploadImage } from '../../utils/upload.js';

export function saveProfile(formData) {
    return updateProfile( formData);
}

export function saveProfileImage(event) {
    return uploadImage(event,'profile_image',uploadProfileImage);
}

export function saveCoverImage(event) {
    return uploadImage(event,'cover_image',uploadCoverImage);
}
