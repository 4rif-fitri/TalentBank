import resumeData from "./data/resume-data.js";
import { measureBlocks } from "./engine/measurement.js";
import { paginateColumn, paginateTwoColumns } from "./engine/pagination.js";
import { renderPages } from "./engine/renderer.js";
import modern from "./templates/modern.js";
import professional from "./templates/professional.js";

const templates = { modern, professional };

let currentTemplate = "professional";
let zoom = 1;

export function renderResume() {

    const template = templates[currentTemplate];

    if (!template) {
        console.error("Template not found:",currentTemplate);
        return;
    }

    let pages;

    if (template.type === "single") {

        const blocks = template.render(resumeData);
        const measured = measureBlocks(blocks);
        const columnPages = paginateColumn(measured);

        pages = columnPages.map(blocks => ({
            left: blocks,
            right: []
        }));
    }

    else if (template.type === "columns") {

        const layout = template.render(resumeData);
        const left = measureBlocks(layout.left);
        const right = measureBlocks(layout.right);
        pages = paginateTwoColumns(left, right);
    }

    renderPages(pages, template);
}

function applyZoom() {
    const resumePages = document.querySelector("#resumePages");
    resumePages.style.scale = zoom
    resumePages.style.transformOrigin = "top center";
}

$(document).ready(function () {

    $("#zoomIn").on("click", function () {
        zoom += 0.1;
        if (zoom > 2) zoom = 2;
        applyZoom();
    });

    $("#zoomOut").on("click", function () {
        zoom -= 0.1;
        if (zoom < 0.5) zoom = 0.5;
        applyZoom();
    });

    renderResume();

    $("#templateSelect").on("change", function () {
        currentTemplate = this.value;
        renderResume();
    });


    $("#downloadResume").on("click", async function () {

        const resumePages = document.querySelector("#resumePages");

        // Simpan zoom asal
        const originalZoom = zoom;

        // Reset scale sebelum capture
        resumePages.style.scale = "1";
        resumePages.style.transformOrigin = "top center";

        try {

            await html2pdf()
                .set({
                    margin: 0,
                    filename: "resume.pdf",
                    image: {
                        type: "jpeg",
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2,
                        useCORS: true
                    },
                    jsPDF: {
                        unit: "mm",
                        format: "a4",
                        orientation: "portrait"
                    }
                })
                .from(resumePages)
                .save();

        } finally {
            resumePages.style.scale = originalZoom;
            resumePages.style.transformOrigin = "top center";
        }
    });
});
