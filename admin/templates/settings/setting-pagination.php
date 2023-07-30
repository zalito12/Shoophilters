<?php
$pagination = WooFilters_Settings::get_option_with_default( WooFilters_Settings::OPTION_PAGINATION );
?>
<select id="woofilters-pagination-type" class="regular-text" name="<?php echo esc_attr( WooFilters_Settings::OPTION_PAGINATION . '[' . WooFilters_Settings::FIELD_PAGINATION_TYPE . ']' ); ?>">
	<option value="<?php echo esc_attr( PaginationType::PAGES ); ?>" <?php selected( PaginationType::PAGES, $pagination[ WooFilters_Settings::FIELD_PAGINATION_TYPE ] ); ?>>
		<?php esc_html_e( 'Pages', 'woofilters' ); ?>
	</option>
	<option value="<?php echo esc_attr( PaginationType::INFINITE ); ?>" <?php selected( PaginationType::INFINITE, $pagination[ WooFilters_Settings::FIELD_PAGINATION_TYPE ] ); ?>>
		<?php esc_html_e( 'Infinite Scroll', 'woofilters' ); ?>
	</option>
</select>
<p>
	<span id="woofilters-pagination-<?php echo esc_attr( PaginationType::PAGES ); ?>" class="hidden">
		<?php esc_html_e( 'Use the standard pages with async load.', 'woofilters' ); ?>
	</span>
	<span id="woofilters-pagination-<?php echo esc_attr( PaginationType::INFINITE ); ?>" class="hidden">
		<?php esc_html_e( 'Use infinite scroll pagination with async load.', 'woofilters' ); ?>
	</span>
</p>

<script>
	(function ($) {
	'use strict';

	$(function () {
		// Helpers for pagination type
		let oldVal = $('#woofilters-pagination-type').val();
		$('#woofilters-pagination-' + oldVal).show();
		$('#woofilters-pagination-type').on('change', (e) => {
			$('#woofilters-pagination-' + oldVal).hide();
			oldVal = $('#woofilters-pagination-type').val()
			$('#woofilters-pagination-' + oldVal).show();
		});
	});
})(jQuery);
</script>
