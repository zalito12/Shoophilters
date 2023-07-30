import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import './editor.scss';

import ServerSideRender from '@wordpress/server-side-render';
import {
	PanelBody,
	ToggleControl,
	PanelRow,
	SelectControl,
} from '@wordpress/components';
import LoadTypeControl from '../controls/load-type-control';
import { totalOptions } from '../controls/types';

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
					title={__('Category settings', 'shoophilters')}
					initialOpen={true}
				>
					<PanelRow>
						<ToggleControl
							label={__('Show empty categories', 'shoophilters')}
							checked={attributes.showEmpty}
							onChange={(val) =>
								setAttributes({ showEmpty: val })
							}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label={__('Show child categories', 'shoophilters')}
							checked={attributes.showChildren}
							onChange={(val) =>
								setAttributes({ showChildren: val })
							}
						/>
					</PanelRow>
					<PanelRow>
						<SelectControl
							label={__('Show product count', 'shoophilters')}
							value={attributes.showTotal}
							onChange={(val) =>
								setAttributes({ showTotal: val })
							}
							options={totalOptions}
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
						block="shoophilters/product-categories"
						attributes={props.attributes}
					/>
				</div>
			</Fragment>
		</div>
	);
}
