<?php
/**
 * Settings admin page template
 *
 * Available variables:
 *
 *  @var Shoophilters_Page_Settings $this
 */

settings_errors( 'shoophilters_messages' );
?>

<div class="wrap">
	<h1><?php echo esc_html( $this::get_page_title() ); ?></h1>
	<form action="options.php" method="POST">
		<?php settings_fields( $this::get_slug() ); ?>
		<?php do_settings_sections( $this::get_slug() ); ?>
		<?php submit_button( __( 'Save' ) ); ?>
	</form>
</div>
