// init...Events
import {
    handleUploadProfileImage,
    handleUploadCoverImage,
    handleProfileEditSubmit,
    showProfileEditModal,
    showActiveEducationModal,
    showAboutModal,
    handleInputAbout,
    handleUpdateAbout,
    showContactModal,
    handleUpdateContact,
    showLinksSocialMediaModal,
    handleAddRowSocialMediaLink,
    handleCencelAddLink,
    handleAddLink,
    handleSelectSocialMedia,
    handleDeleteLink
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
    $(document).on('click', '.social-media-option', handleSelectSocialMedia);


}
