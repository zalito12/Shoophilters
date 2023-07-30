<?php
$pagination = WooFilters_Settings::get_option_with_default( WooFilters_Settings::OPTION_PAGINATION );
?>
<select id="woofilters-scroll-to" class="regular-text" name="<?php echo esc_attr( WooFilters_Settings::OPTION_PAGINATION . '[' . WooFilters_Settings::FIELD_SCROLL_TO . ']' ); ?>">
	<option value="<?php echo esc_attr( ScrollToType::TOP ); ?>" <?php selected( ScrollToType::TOP, $pagination[ WooFilters_Settings::FIELD_SCROLL_TO ] ); ?>>
		<?php esc_html_e( 'Scroll to page top', 'woofilters' ); ?>
	</option>
	<option value="<?php echo esc_attr( ScrollToType::PRODUCTS ); ?>" <?php selected( ScrollToType::PRODUCTS, $pagination[ WooFilters_Settings::FIELD_SCROLL_TO ] ); ?>>
		<?php esc_html_e( 'Scroll to products top', 'woofilters' ); ?>
	</option>
	<option value="<?php echo esc_attr( ScrollToType::CUSTOM ); ?>" <?php selected( ScrollToType::CUSTOM, $pagination[ WooFilters_Settings::FIELD_SCROLL_TO ] ); ?>>
		<?php esc_html_e( 'Scroll to custom point', 'woofilters' ); ?>
	</option>
</select>

<p>
<input type="text"
	id="woofilters-scroll-to-custom"
	class="regular-text"
	placeholder="<?php esc_attr_e( '#my-scroll-top-id', 'woofilters' ); ?>"
	name="<?php echo esc_attr( WooFilters_Settings::OPTION_PAGINATION . '[' . WooFilters_Settings::FIELD_SCROLL_TO_CUSTOM . ']' ); ?>"
	value="<?php echo esc_attr( $pagination[ WooFilters_Settings::FIELD_SCROLL_TO_CUSTOM ] ); ?>" />
</p>

<script>
	(function ($) {
	'use strict';

	$(function () {
		// Scroll custom visibility
		const customType = '<?php echo esc_html( ScrollToType::CUSTOM ); ?>';
		$('#woofilters-scroll-to-custom').hide();
		if ( $('#woofilters-scroll-to').val() === customType) {
			$('#woofilters-scroll-to-custom').show();
		}
		$('#woofilters-scroll-to').on('change', (e) => {
			if ( $('#woofilters-scroll-to').val() === customType) {
				$('#woofilters-scroll-to-custom').show();
			} else {
				$('#woofilters-scroll-to-custom').hide();
			}
		});
	});
})(jQuery);
</script>
