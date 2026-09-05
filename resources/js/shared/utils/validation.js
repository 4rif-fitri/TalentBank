export function validateFileImage(file){
    return !file
}

export function getValidImageUrl(url, fallback) {

    return new Promise(resolve => {
        const img = new Image();
        img.onload = () => resolve(url)
        img.onerror = () => resolve(fallback)
        img.src = url;
    });
}

export function checkFormData(formData){
    console.log(Object.fromEntries(formData));
}

export function validateLenghtText(text,limit){
    return text.lenght >= limit
}

export function isValidUrl(link) {
    try {
        const url = new URL(link);

        return url.protocol === "http:" || url.protocol === "https:";
    } catch {
        return false;
    }
}
