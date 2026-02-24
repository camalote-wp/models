import { resolveSelect } from '@wordpress/data';
import { nanoid } from 'nanoid';
import { normalizeUrl } from './shared';

export async function transformImagesMeta(imagesIds = []) {
	return Promise.all(
		imagesIds.filter(Boolean).map(async (id) => {
			const imageData = await resolveSelect('core').getMedia(id);
			return {
				id: String(parseInt(id, 10)),
				url: imageData?.source_url || '',
				alt: imageData?.alt_text || '',
			};
		}),
	);
}

export function transformAuthorsMeta(authors = []) {
	return authors
		.filter((a) => (a.nombre || a.link || '').trim())
		.map((a) => ({
			id: nanoid(),
			name: typeof a.nombre === 'string' ? a.nombre : '',
			url: typeof a.link === 'string' ? normalizeUrl(a.link) : '',
		}));
}
