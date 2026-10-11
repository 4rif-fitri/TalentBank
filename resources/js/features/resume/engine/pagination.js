import { CONTENT_HEIGHT } from "./measurement.js";

export function paginateColumn(blocks) {
	const pages = [];
	let currentPage = [];
	let currentHeight = 0;

	blocks.forEach(block => {
		const blockHeight = block.height;


		if (currentPage.length > 0 && currentHeight + blockHeight > CONTENT_HEIGHT) {
			pages.push(currentPage);
			currentPage = [];
			currentHeight = 0;
		}
		currentPage.push(block);
		currentHeight += blockHeight;
	});

	if (currentPage.length > 0) pages.push(currentPage);

	return pages;
}

export function paginateTwoColumns(leftBlocks,rightBlocks) {
	const leftPages = paginateColumn(leftBlocks);
	const rightPages = paginateColumn(rightBlocks);

	const totalPages = Math.max(leftPages.length,rightPages.length);
	const pages = [];

	for (let i = 0; i < totalPages; i++) {

		pages.push({
			left:
				leftPages[i] || [],
			right:
				rightPages[i] || []
		});
	}

	return pages;
}
