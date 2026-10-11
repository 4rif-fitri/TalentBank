
const professional = {
    id: "professional",
    type: "columns",
    columns: 2,

    render(data = {}) {
        console.log("Professional template data:", data);

        const left = [];
        const right = [];

        const profile = data.profile ?? {};

        // ==========================================
        // LEFT COLUMN: PROFILE
        // ==========================================

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

        const contactItems = [
            profile.email,
            profile.phone,
            profile.location
        ].filter(Boolean);

        if (contactItems.length > 0) {
            left.push({
                id: "contact",
                html: `
                    <section class="professional-contact">
                        ${contactItems.map(item => `
                            <div>${item}</div>
                        `).join("")}
                    </section>
                `
            });
        }

        // ==========================================
        // LEFT COLUMN: SKILLS
        // ==========================================

        // Utamakan skill yang dipilih dalam Resume Builder.
        const skills = Array.isArray(data.skills)
            ? data.skills
            : Array.isArray(profile.skills)
                ? profile.skills
                : [];

        const skillNames = skills
            .map(skill =>
                typeof skill === "string"
                    ? skill
                    : skill.name ?? skill.skill_name ?? ""
            )
            .filter(Boolean);

        if (skillNames.length > 0) {
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
                        ${skillNames.map(name => `
                            <span class="skill">${name}</span>
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

            education.forEach((item, index) => {
                const educationId =
                    item.id ??
                    item.education_id ??
                    item.programme_id ??
                    index;

                const qualification =
                    item.qualification ??
                    item.programme?.programme_name ??
                    item.programme_name ??
                    "Qualification";

                const institution =
                    item.institution ??
                    item.programme?.organization?.company_name ??
                    item.organization?.company_name ??
                    "Institution";

                const year =
                    item.year ??
                    item.graduation_year ??
                    "";

                const cgpa = item.cgpa
                    ? ` • CGPA ${item.cgpa}`
                    : "";

                right.push({
                    id: `education-${educationId}`,
                    html: `
                        <article class="education-item">
                            <div class="education-title">
                                ${qualification}
                            </div>

                            <div class="education-institution">
                                ${institution}
                            </div>

                            <div class="education-meta">
                                ${year}${cgpa}
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
