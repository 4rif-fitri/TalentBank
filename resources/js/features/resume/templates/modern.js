const modern = {
	id: "modern",
	type: "single",
	columns: 1,

    render(data) {
		const blocks = [];
		blocks.push({
			id: "profile",
			html: ` <header class="modern-profile">
                        <div class="resume-name">
                            ${data.profile.name}
                        </div>
                        <div class="resume-title">
                            ${data.profile.jobTitle}
                        </div>
                        <div class="resume-contact">
                            ${data.profile.email}
                            &nbsp; • &nbsp;
                            ${data.profile.phone}
                            &nbsp; • &nbsp;
                            ${data.profile.location}
                        </div>
                    </header>`
		});

        const templateId = new URLSearchParams(window.location.search).get('template_id');
        if (templateId){

            blocks.push({
                id: "experience-heading",

                html: `<section class="resume-section">
                            <h2 class="resume-section-title">EXPERIENCE</h2>
                    </section>`
            });

            data.experience.forEach(item => {
                blocks.push({
                    id: `experience-${item.id}`,
                    html: `<article class="experience-item">
                                <div class="experience-position">
                                    ${item.position}
                                </div>

                                <div class="experience-company">
                                    ${item.company} | ${item.start} - ${item.end}
                                </div>

                                <div class="experience-description">
                                    ${item.description}
                                </div>
                        </article>`
                });
            });

            blocks.push({
                id: "education-heading",
                html: `<section class="resume-section">
                        <h2 class="resume-section-title">EDUCATION</h2>
                    </section>`
            });

            data.education.forEach(item => {
                blocks.push({
                    id: `education-${item.id}`,
                    html: `<article class="education-item">
                                <div class="education-title">
                                    ${item.qualification}
                                </div>
                                <div class="education-institution">
                                    ${item.institution}
                                </div>
                                <div class="education-meta">
                                    ${item.year}
                                    ${item.cgpa ? ` • CGPA ${item.cgpa}` : ""}
                                </div>
                            </article>`
                });
            });

            blocks.push({
                id: "skills-heading",
                html: ` <section class="resume-section">
                            <h2 class="resume-section-title">SKILLS</h2>
                        </section>`
            });


            blocks.push({
                id: "skills",
                html: ` <div class="skills-list">
                            ${data.skills.map(skill => `<span class="skill">${skill.name}</span>`).join("")}
                        </div>`
            });
        }

		return blocks;
	}
};

export default modern;
