<?php

use GonGarceIO\WooFilters\Includes\Woofilters_Product_Query;

$filtering  = $attributes['filtering'];
$value_type = $attributes['endsValue']; // fixed, calculated.
$min_value  = $attributes['minValue'];
$max_value  = $attributes['maxValue'];
$step       = $attributes['step'];
$min_range  = absint( $attributes['minRange'] );
$max_range  = absint( $attributes['maxRange'] );

$product_query = new Woofilters_Product_Query();
if ( 'fixed' !== $value_type ) {
	$prices    = $product_query->get_filtered_price();
	$min_value = absint( $prices->min_price );
	$max_value = absint( $prices->max_price ) + 9;
	$min_value = $min_value - ( $min_value % 10 );
	$max_value = $max_value - ( $max_value % 10 );
}

if ( $min_range > 0 ) {
	$min_value = $min_range;
}

if ( $max_range > 0 ) {
	$max_value = $max_range;
}

$current_min = get_query_var( 'min_price', -1 );
$current_max = get_query_var( 'max_price', -1 );

$group     = $filtering['group'];

?>
<div class="<?php echo esc_attr( $attributes['className'] ); ?>">
	<div class="woofilters-slider-range woofilters-slider-price"
		data-navigation="<?php echo esc_attr( $filtering['navigation'] ); ?>"
		data-group="<?php echo esc_attr( $group ); ?>"
		data-min="<?php echo esc_attr( $min_value ); ?>"
		data-max="<?php echo esc_attr( $max_value ); ?>"
		data-step="<?php echo esc_attr( $step ); ?>"
		data-mask="<?php echo esc_attr( get_woocommerce_price_format() ); ?>"
		data-symbol="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>"
		data-filter-0="min_price"
		data-filter-1="max_price">
		<div class="woofilters-slider-range-label"><?php echo esc_html( $min_value . '€ - ' . $max_value . '€' ); ?></div>
	</div>
</div>
