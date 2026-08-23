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
