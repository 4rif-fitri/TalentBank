export function createBlockElement(block) {
	const wrapper = document.createElement("div");
	wrapper.className = "resume-block";
	wrapper.dataset.blockId = block.id;
	wrapper.innerHTML = block.html;
	return wrapper;
}

export function renderPages(pages,template) {
	const container = document.querySelector("#resumePages");
	container.innerHTML = "";

	pages.forEach((pageData, pageIndex) => {

		const page = document.createElement("div");
		page.className = "resume-page";

		const inner = document.createElement("div");
		inner.className = "resume-page-inner";

		/* SINGLE COLUMN */
		if (template.type === "single") {
			const column = document.createElement("div");
			column.className = "resume-column single-column";

			pageData.left.forEach(block => {
				column.appendChild(createBlockElement(block));
			});
			inner.appendChild(column);
		}

		/* TWO COLUMN */
		if (template.type === "columns") {

			const columns = document.createElement("div");
			columns.className = "resume-columns";
			const left = document.createElement("div");
			left.className = "resume-column resume-left";
			const right = document.createElement("div");
			right.className = "resume-column resume-right";

			pageData.left.forEach(block => {
				left.appendChild(createBlockElement(block));
			});

			pageData.right.forEach(block => {
				right.appendChild(createBlockElement(block));
			});

			columns.appendChild(left);
			columns.appendChild(right);
			inner.appendChild(columns);
		}

		page.appendChild(inner);
		container.appendChild(page);
	});


	const pageCount = document.querySelector("#pageCount");

	if (pageCount) pageCount.textContent = pages.length;
}
