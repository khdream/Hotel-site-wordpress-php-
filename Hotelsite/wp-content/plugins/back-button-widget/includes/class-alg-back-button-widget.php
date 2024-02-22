<?php
/**
 * Back Button Widget - Main Class.
 *
 * @version 1.5.3
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_Back_Button_Widget' ) ) :

final class Alg_Back_Button_Widget {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = ALG_BACK_BUTTON_WIDGET_VERSION;

	/**
	 * @var   Alg_Back_Button_Widget The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_Back_Button_Widget Instance.
	 *
	 * Ensures only one instance of Alg_Back_Button_Widget is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @static
	 * @return  Alg_Back_Button_Widget - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_Back_Button_Widget Constructor.
	 *
	 * @version 1.5.3
	 * @since   1.0.0
	 *
	 * @access  public
	 */
	function __construct() {

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Pro
		if ( 'back-button-widget-pro.php' === basename( ALG_BACK_BUTTON_WIDGET_FILE ) ) {
			require_once( 'pro/class-alg-back-button-widget-pro.php' );
		}

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}

		// Fontawesome
		add_action( 'wp_head', array( $this, 'fontawesome' ) );

	}

	/**
	 * fontawesome.
	 *
	 * @version 1.5.3
	 * @since   1.5.3
	 *
	 * @see     https://cdnjs.com/libraries/font-awesome
	 *
	 * @todo    [next] (dev) `$this->options`?
	 */
	function fontawesome() {
		$options = get_option( 'alg_back_button', array() );
		if ( ! empty( $options['fontawesome_enabled'] ) && 'yes' === $options['fontawesome_enabled'] && ! empty( $options['fontawesome_url'] ) ) {
			?><link rel="stylesheet" href="<?php echo $options['fontawesome_url']; ?>" crossorigin="anonymous" referrerpolicy="no-referrer" /><?php
		}
	}

	/**
	 * localize.
	 *
	 * @version 1.5.0
	 * @since   1.3.0
	 */
	function localize() {
		load_plugin_textdomain( 'back-button-widget', false, dirname( plugin_basename( ALG_BACK_BUTTON_WIDGET_FILE ) ) . '/langs/' );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 */
	function includes() {
		require_once( 'alg-back-button-functions.php' );
		require_once( 'class-alg-back-button-wp-widget.php' );
	}

	/**
	 * admin.
	 *
	 * @version 1.5.0
	 * @since   1.2.1
	 */
	function admin() {
		// Settings
		require_once( 'settings/class-alg-back-button-settings.php' );
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( ALG_BACK_BUTTON_WIDGET_FILE ), array( $this, 'action_links' ) );
		// Version update
		if ( get_option( 'alg_back_button_widget_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}
	}

	/**
	 * action_links.
	 *
	 * @version 1.5.0
	 * @since   1.2.1
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'options-general.php?page=alg-back-button-settings' ) . '">' . __( 'Settings', 'back-button-widget' ) . '</a>';
		if ( 'back-button-widget.php' === basename( ALG_BACK_BUTTON_WIDGET_FILE ) ) {
			$custom_links[] = '<a target="_blank" style="font-weight: bold; color: green;" href="https://wpfactory.com/item/back-button-widget-wordpress-plugin/">' .
				__( 'Go Pro', 'back-button-widget' ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * version_updated.
	 *
	 * @version 1.2.1
	 * @since   1.2.1
	 */
	function version_updated() {
		update_option( 'alg_back_button_widget_version', $this->version );
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( ALG_BACK_BUTTON_WIDGET_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( ALG_BACK_BUTTON_WIDGET_FILE ) );
	}

}

endif;
