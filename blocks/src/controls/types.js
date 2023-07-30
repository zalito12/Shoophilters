import { __ } from '@wordpress/i18n';

export const totalOptions = [
	{
		value: 'never',
		label: __('Never', 'shoophilters'),
	},
	{
		value: 'always',
		label: __('Always', 'shoophilters'),
	},
	{
		value: 'current',
		label: __('Only when selected', 'shoophilters'),
	},
];

export const valueOptions = [
	{
		value: 'fixed',
		label: __('Fixed', 'shoophilters'),
	},
	{
		value: 'calculated',
		label: __('Calculated', 'shoophilters'),
	},
];

export default { totalOptions, valueOptions };
