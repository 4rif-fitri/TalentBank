import { validateFileImage } from "./validation"

export async function uploadImage(event, fieldName, uploadApi) {
    const file = event.target.files[0];

    if (!file) return null
    if (validateFileImage(file)) return null;

    const formData = new FormData();
    formData.append(fieldName, file);

    try {
        const response = await uploadApi(formData);
        return response;

    } catch (error) {
        console.error(`Failed to upload ${fieldName}:`,error);
        throw error;
    }
}
