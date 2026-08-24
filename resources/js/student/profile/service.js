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

import {
    skillsStore, skillsUpdate, skillsDelete
} from "../../api/skill.js"

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
export function updateLanguage(id, language_id, proficiency_level) {
    let formData = new FormData()
    formData.append("language_id", language_id)
    formData.append("proficiency_level", proficiency_level)
    formData.append("_method", "PUT");
    return languagesUpdate(id, formData)
}
export function deleteLanguage(id) {
    return languagesDelete(id)
}

export function storeSkills(source_type, profisource_idciency, skill_id) {
    let formData = new FormData()
    formData.append("source_type", source_type)
    formData.append("source_id", source_id)
    formData.append("skill_id", skill_id)
    return skillsStore(formData)
}
export function updateSkills(id, source_type, source_id, skill_id) {
    let formData = new FormData()
    formData.append("source_type", source_type)
    formData.append("source_id", source_id)
    formData.append("skill_id", skill_id)
    formData.append("_method", "PUT");
    return skillsUpdate(id, formData)
}
export function deleteSkills(id) {
    return skillsDelete(id)
}
