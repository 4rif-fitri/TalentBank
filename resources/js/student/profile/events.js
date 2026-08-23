import { uploadProfileImage, uploadCoverImage } from "../../api/profile"
import { uploadImage } from "../../utils/upload.js"
import { renderCoverImages, renderProfileImages } from "./renderer.js"
import { alertSuccess } from "../../utils/alert"

export function initProfileEvents() {
    $(document).on('click', '#seeMoreActiveEducations', showActiveEducationModal);
    $(document).on('change', '#profileImageInput', handleUploadProfileImage);
    $(document).on('change', '#coverImageInput', handleUploadCoverImage);
}

function showActiveEducationModal() {
    $('#activeEducationModal').modal('show');
}

async function handleUploadProfileImage(event) {
    const response = await uploadImage(event,'profile_image',uploadProfileImage);
    if (!response) return;
    alertSuccess("Success", "Profile image uploaded successfully","success")
    renderProfileImages(response.data);
}

async function handleUploadCoverImage(event) {
    const response = await uploadImage(event, 'cover_image', uploadCoverImage);
    if (!response) return;
    alertSuccess("Success", "Cover image uploaded successfully", "success")
    renderCoverImages(response.data);
}
