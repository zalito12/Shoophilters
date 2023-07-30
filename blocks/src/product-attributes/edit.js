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

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @param  props
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
const totalOptions = [
	{
		value: 'never',
		label: 'Never',
	},
	{
		value: 'always',
		label: 'Always',
	},
	{
		value: 'current',
		label: 'Only when selected',
	},
];

const styleOptions = [
	{
		value: 'list',
		label: 'List',
	},
	{
		value: 'grid',
		label: 'Grid',
	},
];

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
							title: __('Edit', 'woofilters'),
							onClick: () => setAttributes({ taxonomy: '' }),
							isActive: isEditing,
						},
					]}
				/>
			</BlockControls>
		);
	};

	const gridConfigControls = () => {
		if ('grid' !== attributes.style) {
			return;
		}

		return (
			<PanelRow>
				<TextControl
					label="Items per row"
					type="number"
					value={attributes.rowSize}
					onChange={(val) => setAttributes({ rowSize: val })}
				/>
			</PanelRow>
		);
	};

	const getSideControls = () => {
		return (
			<InspectorControls>
				<PanelBody
					title={__('Attribute settings', 'woofilters')}
					initialOpen={true}
				>
					<PanelRow>
						<ToggleControl
							label="Show empty attributes"
							checked={attributes.showEmpty}
							onChange={(val) =>
								setAttributes({ showEmpty: val })
							}
						/>
					</PanelRow>
					<PanelRow>
						<SelectControl
							label="Show product count"
							value={attributes.showTotal}
							onChange={(val) =>
								setAttributes({ showTotal: val })
							}
							options={totalOptions}
						/>
					</PanelRow>
				</PanelBody>
				<PanelBody
					title={__('List settings', 'woofilters')}
					initialOpen={true}
				>
					<PanelRow>
						<RadioControl
							label="Style"
							selected={attributes.style}
							onChange={(val) => setAttributes({ style: val })}
							options={styleOptions}
						/>
					</PanelRow>
					{gridConfigControls()}
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
					label="Select attribute to filter"
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

	if (attributes.taxonomy === '' || isEditing) {
		isEditing = true;
		if (!taxonomies) {
			return <div {...useBlockProps()}>Loading attributes...</div>;
		}

		return (
			<div {...useBlockProps()}>
				{getSelectAttributeControl(taxonomies)}
			</div>
		);
	}

	return (
		<div {...useBlockProps()}>
			<Fragment>
				{getBlockControls()}
				{getSideControls()}
				<div>
					<ServerSideRender
						block="woofilters/product-attributes"
						attributes={props.attributes}
					/>
				</div>
			</Fragment>
		</div>
	);
}
