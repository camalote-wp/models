import { isUri } from './shared';

function validateImage(image) {
	if (typeof image !== 'object' || image === null) return false;
	return (
		typeof image.id === 'string' &&
		typeof image.url === 'string' &&
		typeof image.alt === 'string'
	);
}

function validateAuthor(author) {
	if (typeof author !== 'object' || author === null) return false;
	return (
		typeof author.id === 'string' && typeof author.name === 'string' && isUri(author.url, true)
	);
}

export function validateObjectShape(meta) {
	if (typeof meta !== 'object' || meta === null) return false;

	const {
		'et-models_fotoperiodismo_bajada': bajada,
		'et-models_fotoperiodismo_excerpt': excerpt,
		'et-models_fotoperiodismo_authors': authors,
		'et-models_fotoperiodismo_images': images,
	} = meta;

	if (typeof bajada !== 'string') return false;
	if (typeof excerpt !== 'string') return false;
	if (!Array.isArray(authors) || !authors.every(validateAuthor)) return false;
	if (!Array.isArray(images) || !images.every(validateImage)) return false;

	return true;
}
