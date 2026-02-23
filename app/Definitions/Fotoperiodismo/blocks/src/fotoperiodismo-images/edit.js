import {
	useBlockProps,
	useInnerBlocksProps,
	store as blockEditorStore,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import { createBlock } from '@wordpress/blocks';
import { Button } from '@wordpress/components';
import { usePostMetaValue } from '@10up/block-components';
import { useDispatch, useSelect } from '@wordpress/data';
import { useEffect, useRef } from '@wordpress/element';
import './editor.scss';

/** @typedef {import('@wordpress/element').WPElement} WPElement */

const META_KEY = 'et-models_fotoperiodismo_images';

/**
 * Edit component for the `enfantterrible/fotoperiodismo-images` block.
 *
 * Responsibilities:
 * - Hydrate InnerBlocks from meta on first render.
 * - Sync meta order to inner blocks order after every drag.
 * - Provide UI for inserting multiple `fotoperiodismo-images-item` blocks at once.
 *
 * @param {object} props
 * @param {string} props.clientId
 * @returns {WPElement}
 */
export const BlockEdit = ({ clientId }) => {
	const blockProps = useBlockProps();
	const [images, setImages] = usePostMetaValue(META_KEY);
	const { replaceInnerBlocks } = useDispatch(blockEditorStore);
	const hasHydrated = useRef(false);

	const innerBlocks = useSelect(
		(select) => select('core/block-editor').getBlocks(clientId),
		[clientId],
	);

	/**
	 * Hydrate InnerBlocks from meta on first render only.
	 */
	useEffect(() => {
		if (images === undefined) return;
		if (innerBlocks.length > 0) return;
		if (images.length === 0) return;

		const blocks = images.map((image) =>
			createBlock('enfantterrible/fotoperiodismo-images-item', { id: image.id }),
		);

		replaceInnerBlocks(clientId, blocks, false);
		hasHydrated.current = true;
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [images]);

	/**
	 * After every inner blocks change (including drag), reorder meta to match.
	 */
	useEffect(() => {
		if (!hasHydrated.current) return;
		if (!images?.length || !innerBlocks.length) return;
		if (innerBlocks.length !== images.length) return;

		const reordered = innerBlocks
			.map((block) => images.find((img) => img.id === block.attributes.id))
			.filter(Boolean);

		if (reordered.length === images.length) {
			setImages(reordered);
		}
	}, [innerBlocks]); // eslint-disable-line react-hooks/exhaustive-deps

	/**
	 * Add one block + one meta entry per selected image.
	 * Deduplicates against already existing images by attachment ID.
	 */
	const addItems = (selected) => {
		const existingIds = new Set((images || []).map((i) => i.id));
		const fresh = selected.filter((img) => !existingIds.has(String(img.id)));
		if (!fresh.length) return;

		const newMeta = fresh.map((img) => ({
			id: String(img.id),
			url: img.url,
			alt: img.alt ?? '',
		}));

		const newBlocks = fresh.map((img) =>
			createBlock('enfantterrible/fotoperiodismo-images-item', { id: String(img.id) }),
		);

		setImages([...(images || []), ...newMeta]);
		replaceInnerBlocks(clientId, [...innerBlocks, ...newBlocks], false);
	};

	const { children, ...innerBlocksProps } = useInnerBlocksProps(blockProps, {
		allowedBlocks: ['enfantterrible/fotoperiodismo-images-item'],
		templateLock: false,
		renderAppender: false,
	});

	return (
		<div {...innerBlocksProps}>
			<div className="wp-block-enfantterrible-fotoperiodismo-images-header">
				<p>Imágenes</p>
			</div>
			{children}
			<MediaUploadCheck>
				<MediaUpload
					onSelect={addItems}
					allowedTypes={['image']}
					multiple
					value={(images || []).map((i) => i.id)}
					render={({ open }) => (
						<Button onClick={open} variant="primary">
							+ Agregar imágenes
						</Button>
					)}
				/>
			</MediaUploadCheck>
		</div>
	);
};
