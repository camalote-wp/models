import {
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';

/**
 * Edit component for the example block.
 */
export const BlockEdit = () => {
	const blockProps = useBlockProps();

	const innerBlocksProps = useInnerBlocksProps( blockProps );

	return <div { ...innerBlocksProps } />;
};