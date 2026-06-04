import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Spinner } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import {
	ContentPicker,
	PostContext,
	PostFeaturedImage,
	PostTitle,
	PostAuthor,
	PostDate,
} from '@10up/block-components';
import './editor.scss';

const PostPreview = ({ postLink }) => (
	<div className="wp-block-camalote-wp-nota-de-tapa__inner">
		<div className="wp-block-camalote-wp-nota-de-tapa__media">
			<a href={postLink} className="wp-block-camalote-wp-nota-de-tapa__image-link">
				<PostFeaturedImage className="wp-block-camalote-wp-nota-de-tapa__image" />
			</a>
		</div>
		<div className="wp-block-camalote-wp-nota-de-tapa__content">
			<a href={postLink} className="wp-block-camalote-wp-nota-de-tapa__title-link">
				<PostTitle tagName="h1" className="wp-block-camalote-wp-nota-de-tapa__title" />
			</a>
			<div className="wp-block-camalote-wp-nota-de-tapa__meta">
				<PostAuthor className="wp-block-camalote-wp-nota-de-tapa__author">
					{(author) => (
						author?.link
							? <a href={author.link} className="wp-block-camalote-wp-nota-de-tapa__author-link"><span className="wp-block-camalote-wp-nota-de-tapa__author-name">{author.name}</span></a>
							: <span className="wp-block-camalote-wp-nota-de-tapa__author-name">{author.name}</span>
					)}
				</PostAuthor>
				<PostDate className="wp-block-camalote-wp-nota-de-tapa__date" />
			</div>
		</div>
	</div>
);

export const BlockEdit = ({ attributes, setAttributes }) => {
	const { selectedPost } = attributes;

	const pickedItems = selectedPost?.id
		? [{ id: selectedPost.id, type: selectedPost.type ?? 'post' }]
		: [];

	const post = useSelect(
		(select) => {
			if (!selectedPost?.id) return null;
			return select(coreStore).getEntityRecord(
				'postType',
				selectedPost.type ?? 'post',
				selectedPost.id
			);
		},
		[selectedPost]
	);

	function handlePickChange(picked) {
		if (!picked?.length) {
			setAttributes({ selectedPost: null });
			return;
		}
		const { id, type } = picked[0];
		setAttributes({ selectedPost: { id, type } });
	}

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Post selection', 'camalote-wp')}>
					<ContentPicker
						label={__('Select a post', 'camalote-wp')}
						mode="post"
						contentTypes={['post', 'page']}
						maxContentItems={1}
						content={pickedItems}
						onPickChange={handlePickChange}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...useBlockProps( { className: 'alignwide' })}>
				{!selectedPost?.id && (
					<p className="wp-block-camalote-wp-nota-de-tapa__placeholder">
						{__('Select a post in the sidebar.', 'camalote-wp')}
					</p>
				)}
				{selectedPost?.id && !post && <Spinner />}
				{post && (
					<PostContext
						postId={selectedPost.id}
						postType={selectedPost.type ?? 'post'}
						isEditable={false}
					>
						<PostPreview postLink={post.link ?? null} />
					</PostContext>
				)}
			</div>
		</>
	);
};