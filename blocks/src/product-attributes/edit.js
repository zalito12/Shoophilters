import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	BlockControls,
} from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import './editor.scss';
import LoadTypeControl from '../controls/load-type-control';

import ServerSideRender from '@wordpress/server-side-render';
import {
	PanelBody,
	ToggleControl,
	PanelRow,
	SelectControl,
	RadioControl,
	TextControl,
	ToolbarGroup,
} from '@wordpress/components';

import { useSelect } from '@wordpress/data';
import { store as coreDataStore } from '@wordpress/core-data';

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
	let isEditing = false;

	const getBlockControls = () => {
		return (
			<BlockControls>
				<ToolbarGroup
					controls={[
						{
							icon: 'edit',
							title: __('Edit', 'shoophilters'),
							onClick: () => setAttributes({ taxonomy: '' }),
							isActive: isEditing,
						},
					]}
				/>
			</BlockControls>
		);
	};

	const getSideControls = () => {
		return (
			<InspectorControls>
				<PanelBody
					title={__('Attribute settings', 'shoophilters')}
					initialOpen={true}
				>
					<PanelRow>
						<ToggleControl
							label={__('Show empty attributes', 'shoophilters')}
							checked={attributes.showEmpty}
							onChange={(val) =>
								setAttributes({ showEmpty: val })
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
				<PanelBody
					title={__('List settings', 'shoophilters')}
					initialOpen={true}
				>
					<PanelRow>
						<TextControl
							label={__('Items per row', 'shoophilters')}
							type="number"
							value={attributes.rowSize}
							onChange={(val) => setAttributes({ rowSize: val })}
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

	const getSelectAttributeControl = (taxonomies) => {
		const attributeOptions = taxonomies
			.filter((t) => t.slug.startsWith('pa_'))
			.map((t) => ({ value: t.slug, label: t.labels.singular_name }));

		return (
			<div>
				<RadioControl
					label={__('Select attribute to filter', 'shoophilters')}
					selected={attributes.taxonomy}
					onChange={(val) => setAttributes({ taxonomy: val })}
					options={attributeOptions}
				/>
			</div>
		);
	};

	const taxonomies = useSelect((select) =>
		select(coreDataStore).getTaxonomies({ type: 'product' })
	);

	const blockProps = useBlockProps();
	if (attributes.taxonomy === '' || isEditing) {
		isEditing = true;
		if (!taxonomies) {
			return <div {...blockProps}>Loading attributes...</div>;
		}

		return (
			<div {...blockProps}>{getSelectAttributeControl(taxonomies)}</div>
		);
	}

	return (
		<div {...blockProps}>
			<Fragment>
				{getBlockControls()}
				{getSideControls()}
				<div>
					<ServerSideRender
						block="shoophilters/product-attributes"
						attributes={props.attributes}
					/>
				</div>
			</Fragment>
		</div>
	);
}
