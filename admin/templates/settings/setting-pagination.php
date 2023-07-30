<?php
$pagination = Shoophilters_Settings::get_option_with_default( Shoophilters_Settings::OPTION_PAGINATION );
?>
<select id="shoophilters-pagination-type" class="regular-text" name="<?php echo esc_attr( Shoophilters_Settings::OPTION_PAGINATION . '[' . Shoophilters_Settings::FIELD_PAGINATION_TYPE . ']' ); ?>">
	<option value="<?php echo esc_attr( PaginationType::PAGES ); ?>" <?php selected( PaginationType::PAGES, $pagination[ Shoophilters_Settings::FIELD_PAGINATION_TYPE ] ); ?>>
		<?php esc_html_e( 'Pages', 'shoophilters' ); ?>
	</option>
	<option value="<?php echo esc_attr( PaginationType::INFINITE ); ?>" <?php selected( PaginationType::INFINITE, $pagination[ Shoophilters_Settings::FIELD_PAGINATION_TYPE ] ); ?>>
		<?php esc_html_e( 'Infinite Scroll', 'shoophilters' ); ?>
	</option>
</select>
<p>
	<span id="shoophilters-pagination-<?php echo esc_attr( PaginationType::PAGES ); ?>" class="hidden">
		<?php esc_html_e( 'Use the standard pages with async load.', 'shoophilters' ); ?>
	</span>
	<span id="shoophilters-pagination-<?php echo esc_attr( PaginationType::INFINITE ); ?>" class="hidden">
		<?php esc_html_e( 'Use infinite scroll pagination with async load.', 'shoophilters' ); ?>
	</span>
</p>

<script>
	(function ($) {
	'use strict';

	$(function () {
		// Helpers for pagination type
		let oldVal = $('#shoophilters-pagination-type').val();
		$('#shoophilters-pagination-' + oldVal).show();
		$('#shoophilters-pagination-type').on('change', (e) => {
			$('#shoophilters-pagination-' + oldVal).hide();
			oldVal = $('#shoophilters-pagination-type').val()
			$('#shoophilters-pagination-' + oldVal).show();
		});
	});
})(jQuery);
</script>
