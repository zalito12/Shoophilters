<?php
$pagination = Shoophilters_Settings::get_option_with_default( Shoophilters_Settings::OPTION_PAGINATION );
?>
<select id="shoophilters-scroll-to" class="regular-text" name="<?php echo esc_attr( Shoophilters_Settings::OPTION_PAGINATION . '[' . Shoophilters_Settings::FIELD_SCROLL_TO . ']' ); ?>">
	<option value="<?php echo esc_attr( ScrollToType::TOP ); ?>" <?php selected( ScrollToType::TOP, $pagination[ Shoophilters_Settings::FIELD_SCROLL_TO ] ); ?>>
		<?php esc_html_e( 'Scroll to page top', 'shoophilters' ); ?>
	</option>
	<option value="<?php echo esc_attr( ScrollToType::PRODUCTS ); ?>" <?php selected( ScrollToType::PRODUCTS, $pagination[ Shoophilters_Settings::FIELD_SCROLL_TO ] ); ?>>
		<?php esc_html_e( 'Scroll to products top', 'shoophilters' ); ?>
	</option>
	<option value="<?php echo esc_attr( ScrollToType::CUSTOM ); ?>" <?php selected( ScrollToType::CUSTOM, $pagination[ Shoophilters_Settings::FIELD_SCROLL_TO ] ); ?>>
		<?php esc_html_e( 'Scroll to custom point', 'shoophilters' ); ?>
	</option>
</select>

<p>
<input type="text"
	id="shoophilters-scroll-to-custom"
	class="regular-text"
	placeholder="<?php esc_attr_e( '#my-scroll-top-id', 'shoophilters' ); ?>"
	name="<?php echo esc_attr( Shoophilters_Settings::OPTION_PAGINATION . '[' . Shoophilters_Settings::FIELD_SCROLL_TO_CUSTOM . ']' ); ?>"
	value="<?php echo esc_attr( $pagination[ Shoophilters_Settings::FIELD_SCROLL_TO_CUSTOM ] ); ?>" />
</p>

<script>
	(function ($) {
	'use strict';

	$(function () {
		// Scroll custom visibility
		const customType = '<?php echo esc_html( ScrollToType::CUSTOM ); ?>';
		$('#shoophilters-scroll-to-custom').hide();
		if ( $('#shoophilters-scroll-to').val() === customType) {
			$('#shoophilters-scroll-to-custom').show();
		}
		$('#shoophilters-scroll-to').on('change', (e) => {
			if ( $('#shoophilters-scroll-to').val() === customType) {
				$('#shoophilters-scroll-to-custom').show();
			} else {
				$('#shoophilters-scroll-to-custom').hide();
			}
		});
	});
})(jQuery);
</script>
