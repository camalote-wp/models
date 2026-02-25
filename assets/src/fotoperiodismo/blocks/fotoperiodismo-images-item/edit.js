import { useBlockProps, BlockMover, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
import { useDispatch, useSelect } from '@wordpress/data';
import { usePostMetaValue } from '@10up/block-components';
import {
	trash as trashIcon,
	replace as replaceIcon,
	starFilled,
	starEmpty,
} from '@wordpress/icons';
import './editor.scss';

const META_KEY = 'et-models_fotoperiodismo_images';

/** @typedef {import('@wordpress/element').WPElement} WPElement */

/**
 * Edit component for a single `enfantterrible/fotoperiodismo-images-item` block.
 *
 * Responsibilities:
 * - Displays the image and a MediaUpload button to select/replace it.
 * - Reads its `id` from in-memory attributes (set by parent via createBlock).
 * - Reads and writes its own slice of meta by matching on `id`.
 * - Provides drag handle via BlockMover for reordering.
 * - Handles its own removal from both meta and InnerBlocks.
 *
 * @param {object} props - Component props.
 * @param {string} props.clientId - Unique identifier for the block instance.
 * @param {{ id: string }} props.attributes - Block attributes. Only `id` is stored here; data lives in meta.
 * @returns {WPElement} The rendered block editing interface.
 */
export const BlockEdit = ({ clientId, attributes }) => {
	const blockProps = useBlockProps();
	const { removeBlock } = useDispatch('core/block-editor');
	const { editPost } = useDispatch('core/editor');
	const { id } = attributes;

	const [images, setImages] = usePostMetaValue(META_KEY);
	const index = (images || []).findIndex((img) => img.id === id);
	const image = images?.[index] ?? { url: '', alt: '' };

	// Read the current featured image ID from the post
	const currentFeaturedMediaId = useSelect(
		(select) => select('core/editor').getEditedPostAttribute('featured_media'),
		[],
	);

	const isFeatureImage = image.id && parseInt(image.id, 10) === currentFeaturedMediaId;

	const onSelectImage = (selected) => {
		const updated = [...images];
		updated[index] = {
			id: String(selected.id),
			url: selected.url,
			alt: selected.alt ?? '',
		};
		setImages(updated);
	};

	const removeImage = () => {
		if (isFeatureImage) {
			editPost({ featured_media: 0 });
		}
		setImages(images.filter((_, i) => i !== index));
		removeBlock(clientId, true);
	};

	const toggleFeatureImage = () => {
		if (isFeatureImage) {
			editPost({ featured_media: 0 });
		} else {
			editPost({ featured_media: parseInt(image.id, 10) });
		}
	};

	return (
		<div {...blockProps}>
			<MediaUploadCheck>
				<MediaUpload
					onSelect={onSelectImage}
					allowedTypes={['image']}
					value={image.id ?? undefined}
					render={({ open }) => (
						<div
							className={`image-item__media ${isFeatureImage ? 'image-item__media--featured' : ''}`}
						>
							{image.url ? (
								<img src={image.url} alt={image.alt} />
							) : (
								<div className="image-item__placeholder" />
							)}
							<div className="image-item__data">
								<p>ID: {image.id}</p>
								<p>Nombre: {image.url.split('/').pop()}</p>
								<div className="image-item__actions">
									<Button
										variant={isFeatureImage ? 'primary' : 'secondary'}
										icon={isFeatureImage ? starFilled : starEmpty}
										onClick={toggleFeatureImage}
										disabled={!image.url}
									>
										{isFeatureImage ? 'Destacada' : 'Destacar'}
									</Button>
									<Button variant="primary" icon={replaceIcon} onClick={open}>
										Reemplazar
									</Button>
									<Button
										variant="primary"
										icon={trashIcon}
										onClick={removeImage}
										isDestructive
									>
										Eliminar
									</Button>
								</div>
							</div>
						</div>
					)}
				/>
			</MediaUploadCheck>
			<BlockMover clientIds={[clientId]} hideDragHandle />
		</div>
	);
};
