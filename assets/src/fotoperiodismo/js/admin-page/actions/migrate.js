import { resolveSelect, dispatch } from '@wordpress/data';
import { transformAuthorsMeta, transformImagesMeta } from '../helpers/transforms';
import { validateObjectShape } from '../helpers/validate';

async function getPostWithMeta(item) {
	const post = await resolveSelect('core').getEntityRecord('postType', 'fotoperiodismo', item.id);
	return post?.meta ? post : null;
}

async function getTransformedMeta(meta) {
	const [images, authors] = await Promise.all([
		transformImagesMeta(meta._crb_enfantterrible_fotoperiodismo_gallery),
		transformAuthorsMeta(meta._crb_enfantterrible_fotoperiodismo_authors),
	]);

	return {
		'et-models_fotoperiodismo_authors': authors,
		'et-models_fotoperiodismo_bajada': meta._crb_enfantterrible_fotoperiodismo_desc_long || '',
		'et-models_fotoperiodismo_excerpt':
			meta._crb_enfantterrible_fotoperiodismo_desc_short || '',
		'et-models_fotoperiodismo_images': images,
	};
}

function chunkArray(arr, size) {
	const chunks = [];
	for (let i = 0; i < arr.length; i += size) {
		chunks.push(arr.slice(i, i + size));
	}
	return chunks;
}

async function migratePosts(items) {
	const chunks = chunkArray(items, 5);
	const results = [];

	for (const chunk of chunks) {
		const chunkResults = await Promise.all(
			chunk.map(async (item) => {
				try {
					const post = await getPostWithMeta(item);
					if (!post) return { id: item.id, error: 'Post not found' };

					const transformedMeta = await getTransformedMeta(post.meta);
					if (!validateObjectShape(transformedMeta)) {
						throw new Error('Meta shape validation failed');
					}

					const updatedPost = await dispatch('core').saveEntityRecord(
						'postType',
						'fotoperiodismo',
						{ id: item.id, meta: transformedMeta },
					);

					return { id: item.id, success: true, updatedPost };
				} catch (error) {
					console.error(`Error migrating item ${item.id}:`, error);
					return { id: item.id, error: error.message };
				}
			}),
		);

		results.push(...chunkResults);
	}

	return results;
}

export const migrate = {
	id: 'migrate',
	label: 'Migrate Selected',
	supportsBulk: true,
	icon: 'update',
	isPrimary: true,
	callback: async (items, { onActionPerformed }) => {
		const results = await migratePosts(items);
		if (onActionPerformed) onActionPerformed(results);
	},
};
