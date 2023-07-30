<?php

/**
 * Settings constants and values.
 */
class Shoophilters_Settings {

	public const OPTION_PAGINATION      = 'shoophilters_scroll';
	public const FIELD_PAGINATION_TYPE  = 'paginationType';
	public const FIELD_SCROLL_TO        = 'scrollTo';
	public const FIELD_SCROLL_TO_CUSTOM = 'scrollToCustom';

	public const OPTION_SELECTORS            = 'shoophilters_element_selectors';
	public const FIELD_SELECTOR_RESULT_COUNT = 'resultCount';
	public const FIELD_SELECTOR_CONTENT      = 'content';
	public const FIELD_SELECTOR_PRODUCTS     = 'products';
	public const FIELD_SELECTOR_PAGINATION   = 'pagination';
	public const FIELD_SELECTOR_PAGE         = 'page';

	/**
	 * Gets the content of a setting inside a settings section option.
	 *
	 * @param string $option The section option name.
	 * @return mixed The value of the field or default if not found.
	 */
	public static function get_option_with_default( $option ) {
		return get_option( $option, self::default( $option ) );
	}

	/**
	 * Get the default value for a setting. False if is not set.
	 *
	 * @param string $option The field to get the default value.
	 * @return mixed The default value or false if not defined.
	 */
	public static function default( $option ) {
		switch ( $option ) {
			case self::OPTION_PAGINATION:
				return array(
					self::FIELD_PAGINATION_TYPE  => PaginationType::PAGES,
					self::FIELD_SCROLL_TO        => ScrollToType::TOP,
					self::FIELD_SCROLL_TO_CUSTOM => '',
				);
			case self::OPTION_SELECTORS:
				return array(
					self::FIELD_SELECTOR_RESULT_COUNT => '.woocommerce-result-count',
					self::FIELD_SELECTOR_CONTENT      => '#content',
					self::FIELD_SELECTOR_PRODUCTS     => 'ul.products',
					self::FIELD_SELECTOR_PAGINATION   => '.woocommerce-pagination',
					self::FIELD_SELECTOR_PAGE         => 'ul.page-numbers a',
				);
			default:
				return false;
		}
	}
}

/**
 * Enum for address states.
 * @phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound
 */
abstract class PaginationType {
	public const INFINITE = 'infinite';
	public const PAGES    = 'pages';
}

/**
 * Enum for address states.
 * @phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound
 */
abstract class ScrollToType {
	public const TOP      = 'top';
	public const PRODUCTS = 'products';
	public const CUSTOM   = 'custom';
}
