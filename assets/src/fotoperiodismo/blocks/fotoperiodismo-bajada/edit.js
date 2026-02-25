/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

import { PostMeta } from '@10up/block-components';
import { TextareaControl } from '@wordpress/components';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @returns {Element} Element to render.
 */
const Edit = () => {
	return (
		<div {...useBlockProps()}>
			<div className="wp-block-enfantterrible-fotoperiodismo-bajada-header">
				<p>Bajada</p>
			</div>
			<PostMeta metaKey="et-models_fotoperiodismo_excerpt_short">
				{(shortExcerpt, setShortExcerpt) => (
					<TextareaControl
						__next40pxDefaultSize
						value={shortExcerpt}
						label={__('Bajada corta', 'et-theme')}
						onChange={(value) => setShortExcerpt(value)}
					/>
				)}
			</PostMeta>
			<PostMeta metaKey="et-models_fotoperiodismo_excerpt_long">
				{(longExcerpt, setLongExcerpt) => (
					<TextareaControl
						__next40pxDefaultSize
						value={longExcerpt}
						label={__('Bajada larga', 'et-theme')}
						onChange={(value) => setLongExcerpt(value)}
					/>
				)}
			</PostMeta>
		</div>
	);
};

export default Edit;
