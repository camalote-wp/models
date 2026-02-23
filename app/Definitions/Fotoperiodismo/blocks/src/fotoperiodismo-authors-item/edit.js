// authors-item.js
import { useBlockProps, BlockMover } from '@wordpress/block-editor';
import { TextControl, Button, Toolbar } from '@wordpress/components';
import { useDispatch } from '@wordpress/data';
import { usePostMetaValue } from '@10up/block-components';
import { trash as trashIcon } from '@wordpress/icons';
import './editor.scss';

/** @typedef {import('@wordpress/element').WPElement} WPElement */

/**
 * Edit component for a single `authors-item` block.
 *
 * Responsibilities:
 * - Displays editable fields for author name and URL.
 * - Reads and writes its own slice of meta directly by matching on `id`.
 * - Provides inline toolbar for reordering or deleting the block.
 *
 * @param {object} props - Component props.
 * @param {string} props.clientId - Unique identifier for the block instance.
 * @param {{ id: string }} props.attributes - Block attributes. Only `id` is stored here; data lives in meta.
 * @returns {WPElement} The rendered block editing interface.
 */
export const BlockEdit = ({ clientId, attributes }) => {
	const blockProps = useBlockProps({ className: 'author-item' });
	const { removeBlock } = useDispatch('core/block-editor');
	const { id } = attributes;

	const [authors, setAuthors] = usePostMetaValue('et-models_fotoperiodismo_authors');

	const index = (authors || []).findIndex((a) => a.id === id);
	const author = authors?.[index] ?? { name: '', url: '' };

	/**
	 * Updates a single field in this author's meta slice.
	 *
	 * @param {string} key - The field to update ('name' or 'url').
	 * @returns {(value: string) => void}
	 */
	const updateField = (key) => (value) => {
		const updated = [...authors];
		updated[index] = { ...updated[index], [key]: value };
		setAuthors(updated);
	};

	/**
	 * Removes this author from meta and removes the block.
	 */
	const removeAuthor = () => {
		setAuthors(authors.filter((_, i) => i !== index));
		removeBlock(clientId, true);
	};

	return (
		<div {...blockProps}>
			<div className="wp-block-enfantterrible-fotoperiodismo-authors-item--control-wrapper">
				<TextControl label="Nombre" value={author.name} onChange={updateField('name')} />
				<TextControl label="Enlace" value={author.url} onChange={updateField('url')} />
			</div>
			<Toolbar
				className="wp-block-enfantterrible-fotoperiodismo-authors-item--control-actions"
				label="Options"
				variant="unstyled"
				orientation="vertical"
			>
				<>
					<Button
						icon={trashIcon}
						label="Eliminar autorx"
						onClick={removeAuthor}
						isDestructive
					/>
					<BlockMover clientIds={[clientId]} hideDragHandle />
				</>
			</Toolbar>
		</div>
	);
};
