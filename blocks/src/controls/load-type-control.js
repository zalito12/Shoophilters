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
 * @param {LoadTypeControl} root0            The root.
 * @param {Array}           root0.attributes Attributes.
 * @param {Function}        root0.onChange   On change callback.
 */
const LoadTypeControl = ({ attributes, onChange = () => void 0 }) => {
	const { navigation, group } = attributes;

	return (
		<PanelBody
			title={__('Filtering settings', 'shoophilters')}
			initialOpen={true}
		>
			<PanelRow>
				<SelectControl
					label={__('Filter navigation type', 'shoophilters')}
					help={__(
						'Choose between url navigation, ajax navigation or delegate filters to a button.',
						'shoophilters'
					)}
					value={navigation}
					onChange={(val) => onChange({ navigation: val, group })}
					options={options}
				/>
			</PanelRow>
			<PanelRow>
				<TextControl
					label={__('Filter group id', 'shoophilters')}
					help={__(
						'The filter group id to apply. All filters in the same group will be applied and reseted at the same time.',
						'shoophilters'
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
