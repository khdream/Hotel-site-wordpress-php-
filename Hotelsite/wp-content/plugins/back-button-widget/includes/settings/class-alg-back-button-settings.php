<?php
/**
 * Back Button Widget - Settings.
 *
 * @version 1.5.3
 * @since   1.3.0
 *
 * @author  Algoritmika Ltd.
 *
 * @see     https://codex.wordpress.org/Creating_Options_Pages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Alg_Back_Button_Settings' ) ) :

class Alg_Back_Button_Settings {

	/**
	 * Holds the values to be used in the fields callbacks.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	private $options;

	/**
	 * Constructor.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 *
	 * @todo    [later] (dev) add "reset settings" link
	 */
	function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page (under "Settings").
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	function add_plugin_page() {
		add_options_page(
			__( 'Back Button Settings', 'back-button-widget' ),
			__( 'Back Button', 'back-button-widget' ),
			'manage_options',
			'alg-back-button-settings',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	function create_admin_page() {
		$this->options = get_option( 'alg_back_button', array() );
		echo '<div class="wrap">' . '<h1>' . __( 'Back Button Settings', 'back-button-widget' ) . '</h1>' . '<form method="post" action="options.php">';
		settings_fields( 'alg_back_button_group' );
		do_settings_sections( 'alg-back-button-settings' );
		submit_button();
		echo '</form>' . '</div>';
	}

	/**
	 * Register and add settings.
	 *
	 * @version 1.4.0
	 * @since   1.3.0
	 */
	function page_init() {
		register_setting(
			'alg_back_button_group',
			'alg_back_button',
			array( $this, 'sanitize' )
		);
		$current_section = false;
		foreach ( $this->get_settings() as $option ) {
			if ( 'section' === $option['type'] ) {
				$current_section = $option['id'];
				add_settings_section(
					'alg_back_button_section_' . $option['id'],
					'<span class="dashicons dashicons-admin-page" style="color:green;"></span>' . ' ' . $option['title'],
					array( $this, 'section_desc' ),
					'alg-back-button-settings'
				);
			} else {
				add_settings_field(
					$option['id'],
					$option['title'],
					array( $this, 'output' ),
					'alg-back-button-settings',
					'alg_back_button_section_' . $current_section,
					$option
				);
			}
		}
	}

	/**
	 * Sanitize each setting field as needed.
	 *
	 * @version 1.4.0
	 * @since   1.3.0
	 *
	 * @param   array $input Contains all settings fields as array keys
	 */
	function sanitize( $input ) {
		$new_input = array();
		foreach ( $this->get_settings() as $option ) {
			if ( 'section' === $option['type'] ) {
				continue;
			}
			if ( isset( $input[ $option['id'] ] ) ) {
				switch ( $option['type'] ) {
					case 'multiselect':
						$new_input[ $option['id'] ] = array_map( 'sanitize_text_field', $input[ $option['id'] ] );
						break;
					case 'text':
						$new_input[ $option['id'] ] = wp_kses_post( trim( wp_unslash( $input[ $option['id'] ] ) ) );
						break;
					default: // e.g. `select`, `number`, etc.
						$new_input[ $option['id'] ] = sanitize_text_field( $input[ $option['id'] ] );
				}
			}
		}
		return $new_input;
	}

	/**
	 * output.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 *
	 * @todo    [next] (dev) `multiselect`, `select`: select2
	 * @todo    [maybe] (dev) `multiselect`, `select`: check if `! empty( $select_options )`?
	 */
	function output( $option ) {
		$value    = ( isset( $this->options[ $option['id'] ] ) ?
			( is_array( $this->options[ $option['id'] ] ) ? array_map( 'esc_attr', $this->options[ $option['id'] ] ) : esc_attr( $this->options[ $option['id'] ] ) ) :
			$option['default'] );
		$style    = ( isset( $option['css'] )       ? ' style="' . $option['css']   . '"' : '' );
		$class    = ( isset( $option['class'] )     ? ' class="' . $option['class'] . '"' : '' );
		$atts     = ( isset( $option['atts'] )      ? ' ' . $option['atts']               : '' );
		$desc_tip = ( isset( $option['desc_tip'] )  ? ' ' . $option['desc_tip']           : '' );
		switch ( $option['type'] ) {
			case 'select':
			case 'multiselect':
				$is_multiple    = ( 'multiselect' === $option['type'] );
				$select_options = '';
				foreach ( $option['options'] as $id => $title ) {
					$selected = ( $is_multiple ? selected( in_array( $id, $value ), true, false ) : selected( $value, $id, false ) );
					$select_options .= '<option value="' . $id . '"' . $selected . '>' . $title . '</option>';
				}
				$field = '<select' .
						( $is_multiple ? ' multiple' : '' ) .
						$class .
						$style .
						$atts .
						' id="' . $option['id'] . '"' .
						' name="alg_back_button[' . $option['id'] . ']' . ( $is_multiple ? '[]' : '' ) .
					'">' . $select_options . '</select>' . $desc_tip;
				break;
			default: // e.g. `text`, `number`, etc.
				$field = '<input' .
						$class .
						$style .
						$atts .
						' type="' . $option['type'] . '"' .
						' id="' . $option['id'] . '"' .
						' name="alg_back_button[' . $option['id'] . ']"' .
						' value="' . $value . '"' .
					' />' . $desc_tip;
		}
		echo $field;
		if ( isset( $option['desc'] ) && '' !== $option['desc'] ) {
			$desc_style = ( isset( $option['desc_css'] ) ? ' style="' . $option['desc_css'] . '"' : '' );
			echo '<p' . $desc_style . '>' . $option['desc'] . '</p>';
		}
	}

	/**
	 * section_desc.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function section_desc( $section ) {
		foreach ( $this->get_settings() as $option ) {
			if ( 'section' === $option['type'] && $section['id'] === 'alg_back_button_section_' . $option['id'] && isset( $option['desc'] ) && '' !== $option['desc'] ) {
				echo '<p>' . $option['desc'] . '</p>';
				break;
			}
		}
	}

	/**
	 * get_settings.
	 *
	 * @version 1.5.3
	 * @since   1.4.0
	 *
	 * @todo    [next] (desc) `fontawesome`: better desc?
	 * @todo    [next] (desc) `section_floating_button`: better desc?
	 * @todo    [maybe] (desc) `section_shortcode`: mention `alg_back_button()` function as well?
	 * @todo    [maybe] (desc) `menu_replace_url_enabled`: better desc?
	 */
	function get_settings() {
		return array(
			// Widget section
			array(
				'title'    => __( 'Widget', 'back-button-widget' ),
				'type'     => 'section',
				'id'       => 'widget',
				'desc'     => sprintf( __( 'The plugin creates "%s" widget in %s. You can add and configure it there.', 'back-button-widget' ),
					__( 'Back Button', 'back-button-widget' ),
					'<a target="_blank" href="' . admin_url( 'widgets.php' ) . '">' . __( 'Appearance > Widgets', 'back-button-widget' ) . '</a>' ),
			),
			// Shortcode section
			array(
				'title'    => __( 'Shortcode', 'back-button-widget' ),
				'type'     => 'section',
				'id'       => 'shortcode',
				'desc'     => sprintf( __( 'You can also add the button anywhere on your site with %s shortcode, e.g.: %s', 'back-button-widget' ),
					'<code>[alg_back_button]</code>',
					'<pre style="color:#444444;background-color:#e0e0e0;padding:10px;">[alg_back_button label="Go back"]</pre>' ),
			),
			// Menu Options section
			array(
				'title'    => __( 'Menu Options', 'back-button-widget' ),
				'type'     => 'section',
				'id'       => 'menu',
				'desc'     => __( 'Here you can add the back button to your menu(s).', 'back-button-widget' ),
			),
			array(
				'title'    => __( 'Enable section', 'back-button-widget' ),
				'id'       => 'menu_enabled',
				'default'  => 'no',
				'type'     => 'select',
				'options'  => array(
					'no'  => __( 'No', 'back-button-widget' ),
					'yes' => __( 'Yes', 'back-button-widget' ),
				),
				'atts'     => apply_filters( 'alg_back_button_widget_settings', 'disabled' ),
				'desc'     => apply_filters( 'alg_back_button_widget_settings', 'You will need <a target="_blank" href="https://wpfactory.com/item/back-button-widget-wordpress-plugin/">Back Button Widget Pro</a> plugin to enable this section.' ),
				'desc_css' => 'background-color:white;padding:10px;',
			),
			array(
				'title'    => __( 'Menu location(s)', 'back-button-widget' ),
				'id'       => 'menu_theme_locations',
				'default'  => array(),
				'type'     => 'multiselect',
				'class'    => 'widefat',
				'options'  => get_registered_nav_menus(),
				'desc'     => sprintf( __( 'Alternatively you can use the "%s" option.', 'back-button-widget' ), __( 'Menu(s)', 'back-button-widget' ) ) . '<br>' .
					sprintf( __( 'You can select multiple menus by holding %s key.', 'back-button-widget' ), '<code>' . __( 'Ctrl', 'back-button-widget' ) . '</code>' ) . '<br>' .
					__( 'Button will be added as the last item in selected menu location(s).', 'back-button-widget' ),
			),
			array(
				'title'    => __( 'Menu(s)', 'back-button-widget' ),
				'id'       => 'menu_ids',
				'default'  => array(),
				'type'     => 'multiselect',
				'class'    => 'widefat',
				'options'  => array_combine( wp_list_pluck( wp_get_nav_menus(), 'term_id' ), wp_list_pluck( wp_get_nav_menus(), 'name' ) ),
				'desc'     => sprintf( __( 'Alternatively you can use the "%s" option.', 'back-button-widget' ), __( 'Menu location(s)', 'back-button-widget' ) ) . '<br>' .
					sprintf( __( 'You can select multiple menus by holding %s key.', 'back-button-widget' ), '<code>' . __( 'Ctrl', 'back-button-widget' ) . '</code>' ) . '<br>' .
					__( 'Button will be added as the last item in selected menu(s).', 'back-button-widget' ),
			),
			array(
				'title'    => __( 'Button', 'back-button-widget' ),
				'id'       => 'menu_button',
				'default'  => '',
				'type'     => 'text',
				'class'    => 'widefat',
				'desc'     => sprintf( __( 'If empty, then the default value will be used: %s.', 'back-button-widget' ),
					'<code>' . esc_html( '[alg_back_button label="Back" type="simple"]' ) . '</code>' ),
			),
			array(
				'title'    => __( 'Item template', 'back-button-widget' ),
				'id'       => 'menu_item_template',
				'default'  => '',
				'type'     => 'text',
				'class'    => 'widefat',
				'desc'     => sprintf( __( 'If empty, then the default value will be used: %s.', 'back-button-widget' ),
					'<code>' . esc_html( '<li>%button%<li>' ) . '</code>' ),
			),
			array(
				'title'    => __( 'Replace URL', 'back-button-widget' ),
				'id'       => 'menu_replace_url_enabled',
				'default'  => 'no',
				'type'     => 'select',
				'options'  => array(
					'no'  => __( 'No', 'back-button-widget' ),
					'yes' => __( 'Yes', 'back-button-widget' ),
				),
				'desc'     => sprintf( __( 'This is an alternative method for adding the back button: add a "Custom Link" to your menu(s) (in %s), and set its "URL" to %s.', 'back-button-widget' ),
					'<a target="_blank" href="' . admin_url( 'nav-menus.php' ) . '">' . __( 'Appearance > Menus', 'back-button-widget' ) . '</a>',
					'<code>' . esc_html( '[ALG_BACK_BUTTON]' ) . '</code>' ),
			),
			// Floating Button Options section
			array(
				'title'    => __( 'Floating Button Options', 'back-button-widget' ),
				'type'     => 'section',
				'id'       => 'floating_button',
				'desc'     => __( 'Here you can add the back button as a floating button.', 'back-button-widget' ),
			),
			array(
				'title'    => __( 'Enable section', 'back-button-widget' ),
				'id'       => 'floating_button_enabled',
				'default'  => 'no',
				'type'     => 'select',
				'options'  => array(
					'no'  => __( 'No', 'back-button-widget' ),
					'yes' => __( 'Yes', 'back-button-widget' ),
				),
				'atts'     => apply_filters( 'alg_back_button_widget_settings', 'disabled' ),
				'desc'     => apply_filters( 'alg_back_button_widget_settings', 'You will need <a target="_blank" href="https://wpfactory.com/item/back-button-widget-wordpress-plugin/">Back Button Widget Pro</a> plugin to enable this section.' ),
				'desc_css' => 'background-color:white;padding:10px;',
			),
			array(
				'title'    => __( 'Label', 'back-button-widget' ),
				'id'       => 'floating_button_label',
				'default'  => '',
				'type'     => 'text',
				'class'    => 'widefat',
				'desc'     => sprintf( __( 'If empty, then the default value will be used: %s.', 'back-button-widget' ),
					'<code>' . esc_html( __( 'Back', 'back-button-widget' ) ) . '</code>' ),
			),
			array(
				'title'    => __( 'Position', 'back-button-widget' ),
				'id'       => 'floating_button_position',
				'default'  => 'bottom-right',
				'type'     => 'select',
				'options'  => array(
					'bottom-right' => __( 'Bottom right', 'back-button-widget' ),
					'bottom-left'  => __( 'Bottom left', 'back-button-widget' ),
					'top-right'    => __( 'Top right', 'back-button-widget' ),
					'top-left'     => __( 'Top left', 'back-button-widget' ),
				),
			),
			array(
				'title'    => __( 'Horizontal margin', 'back-button-widget' ),
				'id'       => 'floating_button_h_margin',
				'default'  => '',
				'type'     => 'number',
				'atts'     => 'min="0"',
				'desc'     => sprintf( __( 'If empty, then the default value will be used: %s px.', 'back-button-widget' ),
					'<code>' . '40' . '</code>' ),
				'desc_tip' => __( 'pixels', 'back-button-widget' ),
			),
			array(
				'title'    => __( 'Vertical margin', 'back-button-widget' ),
				'id'       => 'floating_button_v_margin',
				'default'  => '',
				'type'     => 'number',
				'atts'     => 'min="0"',
				'desc'     => sprintf( __( 'If empty, then the default value will be used: %s px.', 'back-button-widget' ),
					'<code>' . '40' . '</code>' ),
				'desc_tip' => __( 'pixels', 'back-button-widget' ),
			),
			array(
				'title'    => __( 'Set as shortcode', 'back-button-widget' ),
				'id'       => 'floating_button_shortcode_enabled',
				'default'  => 'no',
				'type'     => 'select',
				'options'  => array(
					'no'  => __( 'No', 'back-button-widget' ),
					'yes' => __( 'Yes', 'back-button-widget' ),
				),
				'desc'     => sprintf( __( 'When enabled, "%s" option will be ignored.', 'back-button-widget' ), __( 'Label', 'back-button-widget' ) ),
			),
			array(
				'title'    => '',
				'id'       => 'floating_button_shortcode',
				'default'  => '',
				'type'     => 'text',
				'class'    => 'widefat',
				'desc'     => sprintf( __( 'If empty, then the default value will be used: %s.', 'back-button-widget' ),
						'<code>' . esc_html( '[alg_back_button label="Back" type="button" class="alg_back_button_floating"]' ) . '</code>' ) . '<br>' .
					sprintf( __( 'Please note: %s shortcode attribute <strong>must</strong> include %s class.', 'back-button-widget' ),
						'<code>class</code>', '<code>alg_back_button_floating</code>' ),
			),
			// Font Awesome section
			array(
				'title'    => __( 'Font Awesome', 'back-button-widget' ),
				'type'     => 'section',
				'id'       => 'fontawesome',
				'desc'     => sprintf( __( 'If you are not loading Font Awesome anywhere else on your site, and using icon in the button, e.g. %s, you can load it here.', 'back-button-widget' ),
					'<code>[alg_back_button fa="fas fa-angle-double-left"]</code>' ),
			),
			array(
				'title'    => __( 'Load', 'back-button-widget' ),
				'id'       => 'fontawesome_enabled',
				'default'  => 'no',
				'type'     => 'select',
				'options'  => array(
					'no'  => __( 'No', 'back-button-widget' ),
					'yes' => __( 'Yes', 'back-button-widget' ),
				),
			),
			array(
				'title'    => __( 'URL', 'back-button-widget' ),
				'id'       => 'fontawesome_url',
				'default'  => '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css',
				'type'     => 'text',
				'desc'     => sprintf( __( 'E.g.: %s', 'back-button-widget' ), '<code>//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css</code>' ),
				'css'      => 'width:100%;',
			),
		);
	}

}

endif;

return new Alg_Back_Button_Settings();
