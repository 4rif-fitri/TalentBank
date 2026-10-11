
const professional = {
    id: "professional",
    type: "columns",
    columns: 2,

    render(data) {
        console.log("Professional template data:", data);

        const left = [];
        const right = [];

        // ==========================================
        // LEFT COLUMN: PROFILE
        // ==========================================

        const profile = data.profile ?? {};

        left.push({
            id: "profile",
            html: `
                <header class="professional-profile">
                    <div class="resume-name">
                        ${profile.name ?? ""}
                    </div>

                    <div class="resume-title">
                        ${profile.jobTitle ?? ""}
                    </div>
                </header>
            `
        });

        // ==========================================
        // LEFT COLUMN: CONTACT
        // ==========================================

        left.push({
            id: "contact",
            html: `
                <section class="professional-contact">
                    <div>${profile.email ?? ""}</div>
                    <div>${profile.phone ?? ""}</div>
                    <div>${profile.location ?? ""}</div>
                </section>
            `
        });

        // ==========================================
        // LEFT COLUMN: SKILLS
        // ==========================================

        const skills = Array.isArray(profile.skills)
            ? profile.skills
            : Array.isArray(data.skills)
                ? data.skills
                : [];

        if (skills.length > 0) {
            left.push({
                id: "skills-heading",
                html: `
                    <section class="resume-section">
                        <h2 class="resume-section-title">
                            SKILLS
                        </h2>
                    </section>
                `
            });

            left.push({
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

        // ==========================================
        // RIGHT COLUMN: EDUCATION
        // ==========================================

        const education = Array.isArray(data.education)
            ? data.education
            : [];

        if (education.length > 0) {
            right.push({
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
                right.push({
                    id: `education-${item.id
                        ?? item.education_id
                        ?? item.programme_id
                        ?? "item"}`,
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

        // ==========================================
        // RETURN TWO-COLUMN LAYOUT
        // ==========================================

        return {
            left,
            right
        };
    }
};

export default professional;
