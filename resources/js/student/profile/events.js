// init...Events
import {
    handleUploadProfileImage,   handleUploadCoverImage,
    handleProfileEditSubmit,    showProfileEditModal,
    showActiveEducationModal,   showAboutModal,
    handleInputAbout,           handleUpdateAbout,
    showContactModal,           handleUpdateContact,
    showLinksSocialMediaModal,  handleAddRowSocialMediaLink,
    handleCencelAddLink,        handleAddLink,
    handleSelectSocialMedia,    handleDeleteLink,
    handleEditLink,             handleUpdateLink,
    showLanguageMediaModal,     handleAddLanguage,
    handleSaveAddLanguage,      handleDeleteLanguage,
    handleEditLanguage,         handleUpdateLanguage,
    showSkillModal,             handleAddSkill,
    handleEditSkill,            handleSaveNewSkill,
    handleDeleteSkill,          handleCencelAddSkill,
    handleCencelUpdateSkill,    handleUpdateSkill
} from './handlers.js';

import { hideModalAlert } from '../../utils/modal.js';

export function initProfileEvents() {
    $(document).on('change','#profileImageInput',handleUploadProfileImage);
    $(document).on('change','#coverImageInput',handleUploadCoverImage);
    $(document).on('click','#seeMoreActiveEducations',showActiveEducationModal);
    $(document).on('click','#btnEditProfile',showProfileEditModal);
    $(document).on('click', '#btnSaveProfile', handleProfileEditSubmit);
    $(document).on('hide.bs.modal', '.modal', hideModalAlert);
    $(document).on('click', '#btnEditAbout', showAboutModal);
    $(document).on('input', '#aboutInput', handleInputAbout);
    $(document).on('click', '#btnSaveAbout', handleUpdateAbout);
    $(document).on('click', '#btnEditContactInformation', showContactModal);
    $(document).on('click', '#btnSaveContact', handleUpdateContact);
    $(document).on('click', '#btnSocialMediaLink', showLinksSocialMediaModal);
    $(document).on('click', '#addlink', handleAddRowSocialMediaLink);

    $(document).on('click', '.btnCancelAdd', handleCencelAddLink);
    $(document).on('click', '.btnAddSave', handleAddLink);
    $(document).on('click', '.btnDeleteLink', handleDeleteLink);
    $(document).on('click', '.btnEditLink', handleEditLink);
    $(document).on('click', '.btnUpdateLink', handleUpdateLink);
    $(document).on('click', '.social-media-option', handleSelectSocialMedia);

    $(document).on('click', '#btnLanguageLink', showLanguageMediaModal);
    $(document).on('click', '#btnAddLanguage', handleAddLanguage);
    $(document).on('click', '.btn-save-language', handleSaveAddLanguage);
    $(document).on('click', '.btn-delete-language', handleDeleteLanguage);
    $(document).on('click', '.btn-edit-language', handleEditLanguage);
    $(document).on('click', '.btn-update-language', handleUpdateLanguage);

    $(document).on('click', '#btnSkill', showSkillModal);
    $(document).on('click', '#btnAddSkill', handleAddSkill);
    $(document).on('click', '.btn-edit-skill', handleEditSkill);
    $(document).on('click', '.btn-save-new-skill', handleSaveNewSkill);
    $(document).on('click', '.btn-remove-skill', handleDeleteSkill);
    $(document).on('click', '.btn-cencel-addSkill', handleCencelAddSkill);
    $(document).on('click', '.btn-cancel-update-skill', handleCencelUpdateSkill);
    $(document).on('click', '.btn-update-skill', handleUpdateSkill);

}
