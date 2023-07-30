import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import './editor.scss';
import LoadTypeControl from '../controls/load-type-control';

import ServerSideRender from '@wordpress/server-side-render';
import {
	PanelBody,
	PanelRow,
	RadioControl,
	TextControl,
} from '@wordpress/components';

const valueOptions = [
	{
		value: 'fixed',
		label: 'Fixed',
	},
	{
		value: 'calculated',
		label: 'Calculated',
	},
];

export default function Edit(props) {
	const { attributes, setAttributes } = props;

	const valueControls = () => {
		if ('fixed' !== attributes.endsValue) {
			return;
		}

		return (
			<div>
				<PanelRow>
					<TextControl
						label="Min. Price value"
						type="number"
						value={attributes.minValue}
						onChange={(val) => setAttributes({ minValue: val })}
					/>
				</PanelRow>
				<PanelRow>
					<TextControl
						label="Max. Price value"
						type="number"
						value={attributes.maxValue}
						onChange={(val) => setAttributes({ maxValue: val })}
					/>
				</PanelRow>
			</div>
		);
	};

	const getSideControls = () => {
		return (
			<InspectorControls>
				<PanelBody
					title={__('Price settings', 'woofilters')}
					initialOpen={true}
				>
					<PanelRow>
						<RadioControl
							label="Price values type"
							selected={attributes.endsValue}
							onChange={(val) =>
								setAttributes({ endsValue: val })
							}
							options={valueOptions}
						/>
					</PanelRow>
					{valueControls()}
					<PanelRow>
						<TextControl
							label="Step"
							type="number"
							value={attributes.step}
							onChange={(val) => setAttributes({ step: val })}
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

	return (
		<div {...useBlockProps()}>
			<Fragment>
				{getSideControls()}
				<div>
					<ServerSideRender
						block="woofilters/product-price"
						attributes={props.attributes}
					/>
				</div>
			</Fragment>
		</div>
	);
}
