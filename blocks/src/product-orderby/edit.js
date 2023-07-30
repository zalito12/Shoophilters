import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import {
	PanelBody,
	RadioControl,
	PanelRow,
	ToggleControl,
} from '@wordpress/components';

import LoadTypeControl from '../controls/load-type-control.js';

import './editor.scss';

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
					title={__('Input settings', 'shoophilters')}
					initialOpen={true}
				>
					<PanelRow>
						<RadioControl
							label="Input type"
							selected={attributes.type}
							options={[
								{
									label: __('List', 'shoophilters'),
									value: 'list',
								},
								{
									label: __('Dropdow select', 'shoophilters'),
									value: 'select',
								},
							]}
							onChange={(value) => setAttributes({ type: value })}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label={__('Show always', 'shoophilters')}
							help={__(
								'Always show the field, even if no results or there is just one single page.',
								'shoophilters'
							)}
							checked={attributes.showAlways}
							onChange={(val) =>
								setAttributes({ showAlways: val })
							}
						/>
					</PanelRow>
				</PanelBody>
				<LoadTypeControl
					attributes={attributes.filtering}
					onChange={(value) => setAttributes({ filtering: value })}
				/>
			</InspectorControls>
		);
	};

	const getPreview = () => {
		const options = [
			'Order example 1',
			'Order example 2',
			'Order example 3',
		];

		if ('select' === attributes.type) {
			return (
				<select
					name="orderby"
					className="orderby shoophilters-orderby-select"
				>
					{options.map((option, index) => (
						<option key={index}>{option}</option>
					))}
				</select>
			);
		}

		return (
			<ul className="shoophilters-orderby">
				{options.map((option, index) => (
					<li key={index} className="shoophilters-orderby-item">
						<div className="shoophilters-item-radio-label">
							{'button' === attributes.filtering.navigation ? (
								<div>
									<input
										className="shoophilters-radio-filter"
										type="radio"
									/>
									<span className="shoophilters-radio-filter-mark"></span>
								</div>
							) : (
								''
							)}
							<a href="?">
								<span className="shoophilters-orderby-name">
									{option}
								</span>
							</a>
						</div>
					</li>
				))}
			</ul>
		);
	};

	return (
		<div {...useBlockProps()}>
			<Fragment>
				{getSideControls()}
				{getPreview()}
			</Fragment>
		</div>
	);
}
