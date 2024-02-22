<?php
/*
Plugin Name: Back Button Widget
Plugin URI: https://wpfactory.com/item/back-button-widget-wordpress-plugin/
Description: Back button widget for WordPress.
Version: 1.6.1
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: back-button-widget
Domain Path: /langs
*/

defined( 'ABSPATH' ) || exit;

if ( 'back-button-widget.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	$plugin = 'back-button-widget-pro/back-button-widget-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		( is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		return;
	}
}

defined( 'ALG_BACK_BUTTON_WIDGET_VERSION' ) || define( 'ALG_BACK_BUTTON_WIDGET_VERSION', '1.6.1' );

defined( 'ALG_BACK_BUTTON_WIDGET_FILE' ) || define( 'ALG_BACK_BUTTON_WIDGET_FILE', __FILE__ );

require_once( 'includes/class-alg-back-button-widget.php' );

if ( ! function_exists( 'alg_back_button_widget' ) ) {
	/**
	 * Returns the main instance of Alg_Back_Button_Widget to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_back_button_widget() {
		return Alg_Back_Button_Widget::instance();
	}
}

add_action( 'plugins_loaded', 'alg_back_button_widget' );
