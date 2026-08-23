import { uploadProfileImage, uploadCoverImage } from "../../api/profile"
import { validateFileImage } from "../../utils/validation"

export function initProfileEvents() {
    $(document).on('click', '#seeMoreActiveEducations', showActiveEducationModal);
    $(document).on('change', '#profileImageInput', handleUploadProfileImage);
    $(document).on('change', '#coverImageInput', handleUploadCoverImage);
}

function showActiveEducationModal() {
    $('#activeEducationModal').modal('show');
}

async function handleUploadProfileImage(event){
    let file = event.target.files[0]
    if (validateFileImage(file)) return

    let formData = new FormData()
    formData.append("profile_image", file)

    let response = await uploadProfileImage(formData);
    console.log(response);

}

function handleUploadCoverImage() {

}
