<?php
$selectors = Shoophilters_Settings::get_option_with_default( Shoophilters_Settings::OPTION_SELECTORS );
$default   = Shoophilters_Settings::default( Shoophilters_Settings::OPTION_SELECTORS );
?>

<input type="text"
	class="regular-text"
	placeholder="<?php echo esc_attr( $default[ Shoophilters_Settings::FIELD_SELECTOR_PAGE ] ); ?>"
	name="<?php echo esc_attr( Shoophilters_Settings::OPTION_SELECTORS . '[' . Shoophilters_Settings::FIELD_SELECTOR_PAGE . ']' ); ?>"
	value="<?php echo esc_attr( $selectors[ Shoophilters_Settings::FIELD_SELECTOR_PAGE ] ); ?>" />
