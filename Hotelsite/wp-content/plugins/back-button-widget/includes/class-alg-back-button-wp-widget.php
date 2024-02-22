<?php
/**
 * Back Button Widget - WP Widget.
 *
 * @version 1.5.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Alg_Back_Button_WP_Widget' ) ) :

class Alg_Back_Button_WP_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc.
	 *
	 * @version 1.0.1
	 * @since   1.0.0
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => 'alg_back_button_wp_widget',
			'description' => __( 'Add back button to your site.', 'back-button-widget' ),
		);
		parent::__construct( $widget_ops['classname'], __( 'Back Button', 'back-button-widget' ), $widget_ops );
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 *
	 * @param   array $args
	 * @param   array $instance
	 *
	 * @todo    [now] [!!!] (dev) hide title on empty content, e.g. when "hide on front page"
	 */
	function widget( $args, $instance ) {
		// Prepare function args
		$function_args = $instance;
		$function_args['label']   = $instance['button_label'];
		$function_args['class']   = $instance['button_class'];
		$function_args['style']   = $instance['button_style'];
		$function_args['type']    = $instance['button_type'];
		$function_args['js_func'] = $instance['button_js_func'];
		// HTML
		$html = '';
		$html .= $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			$html .= $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		$html .= $instance['before_button'] . alg_back_button( $function_args['label'], $function_args ) . $instance['after_button'];
		$html .= $args['after_widget'];
		echo $html;
	}

	/**
	 * get_widget_option_fields.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 */
	function get_widget_option_fields() {
		return array(
			'title' => array(
				'title'   => __( 'Title', 'back-button-widget' ),
				'default' => '',
			),
			'before_button' => array(
				'title'   => __( 'Before button', 'back-button-widget' ),
				'default' => '',
			),
			'after_button' => array(
				'title'   => __( 'After button', 'back-button-widget' ),
				'default' => '',
			),
			'button_label' => array(
				'title'   => __( 'Button label', 'back-button-widget' ),
				'default' => __( 'Back', 'back-button-widget' ),
			),
			'button_class' => array(
				'title'   => __( 'Button HTML class', 'back-button-widget' ) .
					'. ' . sprintf( __( 'E.g. try %s', 'back-button-widget' ), '<code>button</code>' ),
				'default' => '',
			),
			'button_style' => array(
				'title'   => __( 'Button HTML style', 'back-button-widget' ) .
					'. ' . sprintf( __( 'E.g. try %s', 'back-button-widget' ), '<code>background-color:red;</code>' ),
				'default' => '',
			),
			'button_type' => array(
				'title'   => __( 'Button type', 'back-button-widget' ),
				'default' => 'input',
				'type'    => 'select',
				'options' => array(
					'input'  => __( 'Button', 'back-button-widget' ),
					'simple' => __( 'Simple text', 'back-button-widget' ),
				),
			),
			'button_js_func' => array(
				'title'   => __( 'Button JS function', 'back-button-widget' ),
				'default' => 'back',
				'type'    => 'select',
				'options' => array(
					'back'   => 'back',
					'go'     => 'go(-1)',
				),
			),
			'hide_on_front_page' => array(
				'title'   => __( 'Hide on front page', 'back-button-widget' ),
				'default' => 'no',
				'type'    => 'select',
				'options' => array(
					'no'  => __( 'No', 'back-button-widget' ),
					'yes' => __( 'Yes', 'back-button-widget' ),
				),
				'desc'    => apply_filters( 'alg_back_button_widget_settings',
					'You will need <a target="_blank" href="https://wpfactory.com/item/back-button-widget-wordpress-plugin/">Back Button Widget Pro</a> plugin to use this option.' ),
				'custom_atts' => apply_filters( 'alg_back_button_widget_settings', 'disabled' ),
			),
			'hide_on_url_param' => array(
				'title'   => __( 'Hide on URL param', 'back-button-widget' ),
				'default' => '',
				'type'    => 'text',
				'desc'    => __( 'Param name', 'back-button-widget' ),
				'custom_atts' => apply_filters( 'alg_back_button_widget_settings', 'disabled' ),
			),
			'hide_on_url_param_value' => array(
				'default' => '',
				'type'    => 'text',
				'desc'    => __( 'Param value', 'back-button-widget' ) . apply_filters( 'alg_back_button_widget_settings',
					'<br>You will need <a target="_blank" href="https://wpfactory.com/item/back-button-widget-wordpress-plugin/">Back Button Widget Pro</a> plugin to use this option.' ),
				'custom_atts' => apply_filters( 'alg_back_button_widget_settings', 'disabled' ),
			),
		);
	}

	/**
	 * Outputs the options form on admin.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 *
	 * @param   array $instance The widget options
	 */
	function form( $instance ) {
		$html = '';
		foreach ( $this->get_widget_option_fields() as $id => $widget_option_field ) {
			$value = ( ! empty( $instance[ $id ] ) ? $instance[ $id ] : $widget_option_field['default'] );
			$label = ( isset( $widget_option_field['title'] ) ? sprintf( '<label for="%s">%s</label>', $this->get_field_id( $id ), $widget_option_field['title'] ) : '' );
			if ( ! isset( $widget_option_field['type'] ) ) {
				$widget_option_field['type'] = 'text';
			}
			if ( ! isset( $widget_option_field['class'] ) ) {
				$widget_option_field['class'] = 'widefat';
			}
			$desc        = ( isset( $widget_option_field['desc'] ) ? '<br><em>' . $widget_option_field['desc'] . '</em>' : '' );
			$custom_atts = ( isset( $widget_option_field['custom_atts'] ) ? ' ' . $widget_option_field['custom_atts'] : '' );
			switch ( $widget_option_field['type'] ) {
				case 'select':
					$options = '';
					foreach ( $widget_option_field['options'] as $option_id => $option_title ) {
						$options .= sprintf( '<option value="%s"%s>%s</option>', $option_id, selected( $option_id, $value, false ), $option_title );
					}
					$field = sprintf( '<select class="' . $widget_option_field['class'] . '" id="%s" name="%s"' . $custom_atts . '>%s</select>',
						$this->get_field_id( $id ), $this->get_field_name( $id ), $options );
					break;
				default: // e.g. 'text'
					$field = sprintf( '<input class="' . $widget_option_field['class'] . '" id="%s" name="%s" type="' . $widget_option_field['type'] . '" value="%s"' . $custom_atts . '>',
						$this->get_field_id( $id ), $this->get_field_name( $id ), esc_attr( $value ) );
			}
			$html .= '<p>' . $label . $field . $desc . '</p>';
		}
		echo $html;
	}

	/**
	 * Processing widget options on save.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @param   array $new_instance The new options
	 * @param   array $old_instance The previous options
	 */
	function update( $new_instance, $old_instance ) {
		foreach ( $this->get_widget_option_fields() as $id => $widget_option_field ) {
			if ( empty( $new_instance[ $id ] ) ) {
				$new_instance[ $id ] = $widget_option_field['default'];
			}
		}
		return $new_instance;
	}
}

endif;

if ( ! function_exists( 'register_alg_back_button_wp_widget' ) ) {
	/**
	 * register Alg_Back_Button_WP_Widget widget.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function register_alg_back_button_wp_widget() {
		register_widget( 'Alg_Back_Button_WP_Widget' );
	}
}
add_action( 'widgets_init', 'register_alg_back_button_wp_widget' );
