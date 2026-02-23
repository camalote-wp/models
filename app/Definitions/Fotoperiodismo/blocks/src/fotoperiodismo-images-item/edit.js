import { useBlockProps, BlockMover, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
import { useDispatch } from '@wordpress/data';
import { usePostMetaValue } from '@10up/block-components';
import { trash as trashIcon, replace as replaceIcon } from '@wordpress/icons';
import './editor.scss';

/** @typedef {import('@wordpress/element').WPElement} WPElement */

const META_KEY = 'et-models_fotoperiodismo_images';

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
 * @param {object} props
 * @param {string} props.clientId
 * @param {{ id: string }} props.attributes
 * @returns {WPElement}
 */
export const BlockEdit = ({ clientId, attributes }) => {
	const blockProps = useBlockProps();
	const { removeBlock } = useDispatch('core/block-editor');
	const { id } = attributes;

	const [images, setImages] = usePostMetaValue(META_KEY);
	const index = (images || []).findIndex((img) => img.id === id);
	const image = images?.[index] ?? { url: '', alt: '' };

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
		setImages(images.filter((_, i) => i !== index));
		removeBlock(clientId, true);
	};

	console.log( 'id:', id, '| images:', images, '| index:', index, '| image:', image );

	return (
		<div {...blockProps}>
			<MediaUploadCheck>
				<MediaUpload
					onSelect={onSelectImage}
					allowedTypes={['image']}
					value={image.id ?? undefined}
					render={({ open }) => (
						<div className="image-item__media">
							{image.url ? (
								<img src={image.url} alt={image.alt} />
							) : (
								<div className="image-item__placeholder" />
							)}
							<div className="image-item__data">
								<p>ID: {image.id}</p>
								<p>Nombre: {image.url.split('/').pop()}</p>
								<div className="image-item__actions">
									<Button variant="primary" icon={replaceIcon} onClick={open}>
										Reemplazar
									</Button>
									<Button icon={trashIcon} onClick={removeImage} isDestructive>
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
