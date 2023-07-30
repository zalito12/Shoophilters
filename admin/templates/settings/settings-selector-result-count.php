<?php
$selectors = WooFilters_Settings::get_option_with_default( WooFilters_Settings::OPTION_SELECTORS );
$default   = WooFilters_Settings::default( WooFilters_Settings::OPTION_SELECTORS );
?>

<input type="text"
	class="regular-text"
	placeholder="<?php echo esc_attr( $default[ WooFilters_Settings::FIELD_SELECTOR_RESULT_COUNT ] ); ?>"
	name="<?php echo esc_attr( WooFilters_Settings::OPTION_SELECTORS . '[' . WooFilters_Settings::FIELD_SELECTOR_RESULT_COUNT . ']' ); ?>"
	value="<?php echo esc_attr( $selectors[ WooFilters_Settings::FIELD_SELECTOR_RESULT_COUNT ] ); ?>" />
