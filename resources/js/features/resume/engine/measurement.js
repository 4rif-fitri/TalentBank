export const MM_TO_PX = 3.7795275591;
export const PAGE_WIDTH = 210 * MM_TO_PX;
export const PAGE_HEIGHT = 297 * MM_TO_PX;
export const PAGE_PADDING_TOP = 15 * MM_TO_PX;
export const PAGE_PADDING_BOTTOM = 15 * MM_TO_PX;
export const CONTENT_HEIGHT = PAGE_HEIGHT - PAGE_PADDING_TOP - PAGE_PADDING_BOTTOM;

export function createMeasurementPage() {
	const page = document.createElement("div");
	page.className = "resume-page measurement-page";
	const inner = document.createElement("div");
	inner.className = "resume-page-inner";
	page.appendChild(inner);
	document.body.appendChild(page);

	return { page, inner };
}

export function measureBlocks(blocks) {
	const {page,inner} = createMeasurementPage();
	const measured = [];

	blocks.forEach(block => {
		const wrapper = document.createElement("div");

		wrapper.className = "resume-block";
		wrapper.dataset.blockId = block.id;
		wrapper.innerHTML = block.html;

		inner.appendChild(wrapper);
		const height = wrapper.getBoundingClientRect().height;
		measured.push({
			...block,
			height
		});

		wrapper.remove();
	});

	page.remove();
	return measured;
}
