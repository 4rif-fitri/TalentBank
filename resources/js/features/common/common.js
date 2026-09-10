export function noSelected(title = "", desc = "", classTarget = "", btnText = ""){
    return `<div class="bg-white border-0 p-3 d-flex flex-column align-items-center">
                <i class="fa-regular fa-folder-open" style="color: rgb(0, 0, 0); font-size: 5rem;"></i>
                <h4 class="mt-2">${title}</h4>
                <p>${desc}</p>
                <button class="btn btn-primary d-block d-lg-none ${classTarget} toggleFilter">
                    <i class="fa-solid fa-filter"></i>
                    ${btnText}
                </button>
            </div>`
}
