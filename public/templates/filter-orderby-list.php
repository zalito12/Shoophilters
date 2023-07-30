<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<ul class="woofilters-orderby">
	<?php foreach ( $catalog_orderby_options as $id => $name ) : // phpcs:ignore ?>
	<li class="woofilters-orderby-item <?php echo $id === $orderby ? 'current ' : ''; ?><?php echo esc_attr( $class ); ?>"
		data-group="<?php echo esc_attr( $group ); ?>"
		data-value="<?php echo esc_attr( $id ); ?>"
		data-filter="orderby">
		<div class="woofilters-item-radio-label">
			<?php if ( $is_button ) : ?>
				<input class="woofilters-radio-filter" type="radio"
					name="woofilters-radio-orderby-<?php echo esc_attr( $group ); ?>"
					value="<?php echo esc_attr( $id ); ?>"
					<?php checked( $orderby, $id ); ?> />
				<span class="woofilters-radio-filter-mark"></span>
			<?php endif; ?>
			<span class="woofilters-orderby-name"><?php echo esc_html( $name ); ?></span>
		</div>
	</li>
	<?php endforeach; ?>
</ul>
<?php

