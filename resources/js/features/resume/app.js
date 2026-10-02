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
        console.error(
            "Template not found:",
            currentTemplate
        );

        return;
    }

    let pages;

    if (template.type === "single") {

        const blocks =
            template.render(resumeData);

        const measured =
            measureBlocks(blocks);

        const columnPages =
            paginateColumn(measured);

        pages = columnPages.map(blocks => ({
            left: blocks,
            right: []
        }));
    }

    else if (template.type === "columns") {

        const layout =
            template.render(resumeData);

        const left =
            measureBlocks(layout.left);

        const right =
            measureBlocks(layout.right);

        pages =
            paginateTwoColumns(left, right);
    }

    renderPages(pages, template);
}


$(document).ready(function () {

    renderResume();

    $("#templateSelect").on("change", function () {

        currentTemplate = this.value;

        renderResume();
    });

    $("#name").on("input", function () {

        resumeData.profile.name =
            this.value;

        renderResume();
    });

    $("#jobTitle").on("input", function () {

        resumeData.profile.jobTitle =
            this.value;

        renderResume();
    });

    $("#email").on("input", function () {

        resumeData.profile.email =
            this.value;

        renderResume();
    });

    $("#phone").on("input", function () {

        resumeData.profile.phone =
            this.value;

        renderResume();
    });

    $("#location").on("input", function () {

        resumeData.profile.location =
            this.value;

        renderResume();
    });

    $("#summary").on("input", function () {

        resumeData.profile.summary =
            this.value;

        renderResume();
    });

    $("#downloadResume").on("click", function () {

        const element =
            document.querySelector("#resumePages");

        html2pdf()
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
            .from(element)
            .save();
    });
});
