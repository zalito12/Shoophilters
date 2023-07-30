/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	SelectControl,
	PanelRow,
	TextControl,
} from '@wordpress/components';
import normalizeId from './group-validator';

const options = [
	{
		value: 'standard',
		label: 'Page navigation',
	},
	{
		value: 'ajax',
		label: 'Async navigation (ajax)',
	},
	{
		value: 'button',
		label: 'Apply filters button',
	},
];

/**
 * LoadTypeControl component.
 *
 * @param root0
 * @param root0.attributes
 * @param root0.onChange
 */
const LoadTypeControl = ({ attributes, onChange = () => void 0 }) => {
	const { navigation, group } = attributes;

	return (
		<PanelBody
			title={__('Filtering settings', 'woofilters')}
			initialOpen={true}
		>
			<PanelRow>
				<SelectControl
					label={__('Filter navigation type', 'woofilters')}
					help={__(
						'Choose between url navigation, ajax navigation or delegate filters to a button.',
						'woofilters'
					)}
					value={navigation}
					onChange={(val) => onChange({ navigation: val, group })}
					options={options}
				/>
			</PanelRow>
			<PanelRow>
				<TextControl
					label={__('Filter group id', 'woofilters')}
					help={__(
						'The filter group id to apply. All filters in the same group will be applied and reseted at the same time.',
						'woofilters'
					)}
					type="text"
					value={group}
					onChange={(val) =>
						onChange({ navigation, group: normalizeId(val) })
					}
				/>
			</PanelRow>
		</PanelBody>
	);
};

export default LoadTypeControl;
