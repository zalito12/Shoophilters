import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';

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
						className={`shoophilters-filter-remove-button`}
						data-group={group}
					>
						{text}
					</button>
				) : (
					<span
						className={`shoophilters-filter-remove-button`}
						data-group={group}
					>
						{text}
					</span>
				)}
			</div>
		);
	},
});
