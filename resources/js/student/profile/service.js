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
    remove,
    update
} from '../../api/socialMedia.js';

import {
    languagesStore,
    languagesDelete,
    languagesUpdate
}from "../../api/language.js"

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
export function updateLink(id, formData) {
    return update(id,formData)
}

export function addLanguage(languageId, proficiency) {
    let formData = new FormData()
    formData.append("language_id",languageId)
    formData.append("proficiency_level",proficiency)
    return languagesStore(formData)
}
export function deleteLanguage(id) {
    return languagesDelete(id)
}
export function updateLanguage(id, language_id, proficiency_level) {
    let formData = new FormData()
    formData.append("language_id", language_id)
    formData.append("proficiency_level", proficiency_level)
    formData.append("_method", "PUT");
    return languagesUpdate(id, formData)
}
