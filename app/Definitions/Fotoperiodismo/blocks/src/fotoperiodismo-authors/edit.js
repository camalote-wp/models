// authors.js
import {
	useBlockProps,
	useInnerBlocksProps,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import { createBlock } from '@wordpress/blocks';
import { Button } from '@wordpress/components';
import { usePostMetaValue } from '@10up/block-components';
import { useDispatch, useSelect } from '@wordpress/data';
import { useEffect, useRef } from '@wordpress/element';
import { nanoid } from 'nanoid';
import './editor.scss';

/** @typedef {import('@wordpress/element').WPElement} WPElement */

/**
 * Edit component for the `enfantterrible-models/authors` block.
 *
 * Responsibilities:
 * - Hydrate InnerBlocks from meta on first render.
 * - Provide UI for inserting additional `authors-item` blocks.
 * - Meta is written directly by each `authors-item` child, not synced here.
 *
 * @param {object} props - Component props.
 * @param {string} props.clientId - The unique client ID of this block instance.
 * @returns {WPElement} Rendered block edit interface.
 */
export const BlockEdit = ({ clientId }) => {
	const blockProps = useBlockProps();0
	const [authors, setAuthors] = usePostMetaValue('et-models_fotoperiodismo_authors');
	const { replaceInnerBlocks } = useDispatch(blockEditorStore);
	const hasHydrated = useRef(false);

	const innerBlocks = useSelect(
		(select) => select('core/block-editor').getBlocks(clientId),
		[clientId],
	);

	/**
	 * Hydrate InnerBlocks from meta on first render only.
	 * No sync back to meta — each authors-item handles its own slice.
	 */
	useEffect(() => {
		if (authors === undefined) return;
		if (innerBlocks.length > 0) return;
		if (authors.length === 0) return;

		const blocks = authors.map((author) =>
			createBlock('enfantterrible/fotoperiodismo-authors-item', {
				id: author.id,
			}),
		);
		replaceInnerBlocks(clientId, blocks, false);
		hasHydrated.current = true;
		// Intentionally run once on mount.
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [authors]);

	/**
	 * After every inner blocks change (including BlockMover reorder), sync meta order.
	 */
	useEffect(() => {
		if (!hasHydrated.current) return;
		if (!authors?.length || !innerBlocks.length) return;
		if (innerBlocks.length !== authors.length) return;

		const reordered = innerBlocks
			.map((block) => authors.find((a) => a.id === block.attributes.id))
			.filter(Boolean);

		if (reordered.length === authors.length) {
			setAuthors(reordered);
		}
	}, [innerBlocks]); // eslint-disable-line react-hooks/exhaustive-deps

	/**
	 * Add a new author to meta and create a corresponding InnerBlock.
	 */
	const addAuthor = () => {
		const id = nanoid();
		setAuthors([...(authors || []), { id, name: '', url: '' }]);
		const existingBlocks = innerBlocks;
		replaceInnerBlocks(
			clientId,
			[
				...existingBlocks,
				wp.blocks.createBlock('enfantterrible/fotoperiodismo-authors-item', { id }),
			],
			false,
		);
	};

	const { children, ...innerBlocksProps } = useInnerBlocksProps(blockProps, {
		allowedBlocks: ['enfantterrible/fotoperiodismo-authors-item'],
		templateLock: false,
		renderAppender: false,
	});

	return (
		<div {...innerBlocksProps}>
			<div className="wp-block-enfantterrible-fotoperiodismo-authors-header">
				<p>Autorxs</p>
			</div>
			{children}
			<Button
				onClick={addAuthor}
				className="wp-block-enfantterrible-fotoperiodismo-authors-add-button"
				variant="primary"
			>
				+ Agregar autorxs
			</Button>
		</div>
	);
};
