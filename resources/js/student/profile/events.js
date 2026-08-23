import {
    handleUploadProfileImage,
    handleUploadCoverImage,
    handleProfileEditSubmit,
    showProfileEditModal,
    showActiveEducationModal
} from './handlers.js';

import {hideModal} from '../../utils/modal.js';

export function initProfileEvents() {
    $(document).on('hide.bs.modal','.modal',hideModal);
    $(document).on('change','#profileImageInput',handleUploadProfileImage);
    $(document).on('change','#coverImageInput',handleUploadCoverImage);
    $(document).on('click','#seeMoreActiveEducations',showActiveEducationModal);
    $(document).on('click','#btnEditProfile',showProfileEditModal);
    $(document).on('click','#btnSaveProfile',handleProfileEditSubmit);
}
