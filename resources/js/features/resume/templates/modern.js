
const modern = {
    id: "modern",
    type: "single",
    columns: 1,

    render(data) {
        const blocks = [];

        // PROFILE
        const profile = data.profile ?? {};

        blocks.push({
            id: "profile",
            html: `
                <header class="modern-profile">
                    <div class="resume-name">
                        ${profile.name ?? ""}
                    </div>

                    <div class="resume-title">
                        ${profile.jobTitle ?? ""}
                    </div>

                    <div class="resume-contact">
                        ${profile.email ?? ""}
                        &nbsp; • &nbsp;
                        ${profile.phone ?? ""}
                        &nbsp; • &nbsp;
                        ${profile.location ?? ""}
                    </div>
                </header>
            `
        });

        // LANGUAGES
        const languages = Array.isArray(data.language)
            ? data.language
            : [];

        if (languages.length > 0) {
            blocks.push({
                id: "languages-heading",
                html: `
            <section class="resume-section">
                <h2 class="resume-section-title">LANGUAGES</h2>
            </section>
        `
            });

            blocks.push({
                id: "languages",
                html: `
            <div class="languages-list">
                ${languages.map(item => `
                    <div class="language-item">
                        <span class="language-name">
                            ${item.language?.name
                    ?? item.name
                    ?? item.language_name
                    ?? "Language"}
                        </span>
                        ${item.proficiency
                        ? `<span class="language-proficiency">
                                — ${item.proficiency}
                               </span>`
                        : ""}
                    </div>
                `).join("")}
            </div>
        `
            });
        }

        // LINKS / SOCIAL MEDIA
        const links = Array.isArray(data.social_media_links)
            ? data.social_media_links
            : [];

        if (links.length > 0) {
            blocks.push({
                id: "links-heading",
                html: `
            <section class="resume-section">
                <h2 class="resume-section-title">LINKS</h2>
            </section>
        `
            });

            blocks.push({
                id: "links",
                html: `
            <div class="links-list">
                ${links.map(item => {
                    const url = item.link ?? item.url ?? "";
                    const name = item.social_media?.name
                        ?? item.name
                        ?? "Link";

                    return `
                        <div class="link-item">
                            <span class="link-name">${name}:</span>
                            <a href="${url}"
                               target="_blank"
                               rel="noopener noreferrer">
                                ${url}
                            </a>
                        </div>
                    `;
                }).join("")}
            </div>
        `
            });
        }

        // EXPERIENCE
        const experiences = Array.isArray(data.experience)
            ? data.experience
            : [];

        if (experiences.length > 0) {
            blocks.push({
                id: "experience-heading",
                html: `
                    <section class="resume-section">
                        <h2 class="resume-section-title">
                            EXPERIENCE
                        </h2>
                    </section>
                `
            });

            experiences.forEach(item => {
                blocks.push({
                    id: `experience-${item.id}`,
                    html: `
                        <article class="experience-item">
                            <div class="experience-position">
                                ${item.position ?? ""}
                            </div>

                            <div class="experience-company">
                                ${item.company ?? ""}
                                | ${item.start ?? ""}
                                - ${item.end ?? ""}
                            </div>

                            <div class="experience-description">
                                ${item.description ?? ""}
                            </div>
                        </article>
                    `
                });
            });
        }

        // EDUCATION
        const education = Array.isArray(data.education)
            ? data.education
            : [];

        if (education.length > 0) {
            blocks.push({
                id: "education-heading",
                html: `
                    <section class="resume-section">
                        <h2 class="resume-section-title">
                            EDUCATION
                        </h2>
                    </section>
                `
            });

            education.forEach(item => {
                blocks.push({
                    id: `education-${item.id ?? item.education_id ?? item.programme_id}`,
                    html: `
                        <article class="education-item">
                            <div class="education-title">
                                ${item.qualification
                        ?? item.programme?.programme_name
                        ?? "Qualification"}
                            </div>

                            <div class="education-institution">
                                ${item.institution
                        ?? item.programme?.organization?.company_name
                        ?? "Institution"}
                            </div>

                            <div class="education-meta">
                                ${item.year ?? ""}
                                ${item.cgpa
                            ? ` • CGPA ${item.cgpa}`
                            : ""}
                            </div>
                        </article>
                    `
                });
            });
        }

        // SKILLS
        const skills = Array.isArray(data.skills)
            ? data.skills
            : [];

        if (skills.length > 0) {
            blocks.push({
                id: "skills-heading",
                html: `
                    <section class="resume-section">
                        <h2 class="resume-section-title">
                            SKILLS
                        </h2>
                    </section>
                `
            });

            blocks.push({
                id: "skills",
                html: `
                    <div class="skills-list">
                        ${skills.map(skill => `
                            <span class="skill">
                                ${skill.name ?? skill}
                            </span>
                        `).join("")}
                    </div>
                `
            });
        }

        return blocks;
    }
};

export default modern;
