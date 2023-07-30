import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';

import './style.scss';

import Edit from './edit';
import metadata from './block.json';

registerBlockType(metadata.name, {
	/**
	 * @see ./edit.js
	 */
	edit: Edit,
	save: (props) => {
		const { attributes } = props;
		const blockProps = useBlockProps.save();

		const { group, text } = attributes;

		return (
			<div {...blockProps}>
				{'button' === attributes.type ? (
					<button
						className={`woofilters-filter-button`}
						data-group={group}
					>
						{text}
					</button>
				) : (
					<span
						className={`woofilters-filter-button`}
						data-group={group}
					>
						{text}
					</span>
				)}
			</div>
		);
	},
});
