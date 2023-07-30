import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import './editor.scss';
import {
	PanelBody,
	PanelRow,
	TextControl,
	ToggleControl,
} from '@wordpress/components';

import normalizeId from '../controls/group-validator';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @param  props
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */

export default function Edit(props) {
	const { attributes, setAttributes } = props;

	const getSideControls = () => {
		return (
			<InspectorControls>
				<PanelBody
					title={__('Button settings', 'woofilters')}
					initialOpen={true}
				>
					<PanelRow>
						<TextControl
							label={__('Filter group id', 'woofilters')}
							help={__(
								'The filter group id to apply. All filters in the same group will be applied when button clicked',
								'woofilters'
							)}
							type="text"
							value={attributes.group}
							onChange={(val) =>
								setAttributes({ group: normalizeId(val) })
							}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label="Use plain text"
							checked={'text' === attributes.type}
							onChange={(val) =>
								setAttributes({ type: val ? 'text' : 'button' })
							}
						/>
					</PanelRow>
					<PanelRow>
						<TextControl
							label={__('Button text', 'woofilters')}
							type="text"
							value={attributes.text}
							onChange={(val) => setAttributes({ text: val })}
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
		);
	};

	return (
		<div {...useBlockProps()}>
			<Fragment>
				{getSideControls()}
				{'button' === attributes.type ? (
					<button className={`woofilters-filter-button`}>
						{attributes.text}
					</button>
				) : (
					<span className={`woofilters-filter-button`}>
						{attributes.text}
					</span>
				)}
			</Fragment>
		</div>
	);
}
