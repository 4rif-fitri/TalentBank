const professional = {
	id: "professional",
	type: "columns",
	columns: 2,

	render(data) {
		const left = [];
		const right = [];

        left.push({
			id: "profile",
			html: `<header class="professional-profile">
                        <div class="resume-name">
                            ${data.profile.name}
                        </div>
                        <div class="resume-title">
                            ${data.profile.jobTitle}
                        </div>
                    </header>`
		});

		left.push({
			id: "contact",
			html: `<section class="professional-contact">
                        <div>${data.profile.email}</div>
                        <div>${data.profile.phone}</div>
                        <div>${data.profile.location}</div>
                    </section>`
		});

		left.push({
			id: "skills-heading",
			html: ` <section class="resume-section">
                        <h2 class="resume-section-title">SKILLS</h2>
                    </section>`
		});

		left.push({
			id: "skills",
			html: `<div class="skills-list">
                        ${data.profile.skills.map(skill => `<span class="skill">${skill.name}</span>`).join("")}
                    </div>`
		});

		/* RIGHT */
		// right.push({
		// 	id: "summary",
		// 	html: `<section class="resume-summary">
        //                <h2 class="resume-section-title">PROFILE</h2>
        //                 <p>${data.profile.summary}</p>
        //             </section>`
		// });

		right.push({
			id: "experience-heading",
			html: `<section class="resume-section">
                        <h2 class="resume-section-title">EXPERIENCE</h2>
                    </section>`
		});

        data.education.forEach(item => {
			right.push({
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

		right.push({
			id: "education-heading",
			html: `<section class="resume-section">
                        <h2 class="resume-section-title">
                            EDUCATION
                        </h2>
                    </section>`
		});

		data.education.forEach(item => {
			right.push({
				id: `education-${item.id}`,
				html: `<article class="education-item">
                            <div class="education-title">
                                ${item.qualification}
                            </div>
                            <div class="education-institution">
                                ${item.institution}
                            </div>
                            <div class="education-meta">
                                ${item.year} • CGPA ${item.cgpa}
                            </div>
                        </article>`
			});
		});

		return {left,right};
	}
};

export default professional;
