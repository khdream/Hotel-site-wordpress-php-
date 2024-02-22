<?php
/**
 * Back Button Widget - Functions.
 *
 * @version 1.6.1
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'alg_back_button' ) ) {
	/**
	 * alg_back_button.
	 *
	 * @version 1.5.2
	 * @since   1.0.0
	 *
	 * @todo    [next] (dev) move `$label` param to `$args`
	 * @todo    [next] (dev) add `title` param
	 * @todo    [next] (dev) https://wordpress.org/support/topic/any-way-to-edit-or-use-this-to-add-a-custom-javascript-link/
	 * @todo    [later] (feature) disable button on "no history"
	 * @todo    [later] (feature) predefined CSS styles
	 * @todo    [maybe] (dev) option to output `<button>` (instead of `<input type="button">`)
	 * @todo    [maybe] (dev) color picker
	 * @todo    [maybe] (feature) option to enable/disable confirmation (and option for confirmation text)
	 */
	function alg_back_button( $label, $args = array() ) {

		$default_args = array(
			'class'                   => '',
			'style'                   => '',
			'type'                    => 'input',
			'js_func'                 => 'back',
			'hide_on_front_page'      => 'no',
			'hide_on_url_param'       => '',
			'hide_on_url_param_value' => '',
			'show_on_url_param'       => '',
			'show_on_url_param_value' => '',
		);
		$args = array_replace( $default_args, $args );

		if ( apply_filters( 'alg_back_button_widget_do_hide', false, $args ) ) {
			return '';
		}

		$label       = ( '' == $label ? __( 'Back', 'back-button-widget' ) : do_shortcode( $label ) );
		$js_function = ( 'back' === $args['js_func'] ? 'back()' : 'go(-1)' );

		switch ( $args['type'] ) {

			case 'href':
				return sprintf( 'javascript:history.%s',
					$js_function );

			case 'simple':
				return sprintf( '<a href="javascript:history.%s" class="alg_back_button_simple %s" style="%s">%s</a>',
					$js_function, $args['class'], $args['style'], $label );

			default: // 'input'
				return sprintf( '<input type="button" value="%s" class="alg_back_button_input %s" style="%s" onclick="window.history.%s" />',
					$label, $args['class'], $args['style'], $js_function );

		}

	}
}

if ( ! function_exists( 'alg_back_button_shortcode' ) ) {
	/**
	 * alg_back_button_shortcode.
	 *
	 * @version 1.6.1
	 * @since   1.0.0
	 *
	 * @todo    [next] (dev) remove `shortcode_atts()`?
	 * @todo    [next] (dev) add `fa` to the widget params (and maybe to the function params as well)
	 */
	function alg_back_button_shortcode( $atts ) {

		$defaults = array(
			'label'                   => __( 'Back', 'back-button-widget' ),
			'class'                   => '',
			'style'                   => '',
			'type'                    => 'input',
			'js_func'                 => 'back',
			'hide_on_front_page'      => 'no',
			'hide_on_url_param'       => '',
			'hide_on_url_param_value' => '',
			'show_on_url_param'       => '',
			'show_on_url_param_value' => '',
			'lang'                    => '',
			'not_lang_text'           => '',
			'fa'                      => '', // e.g. `fas fa-angle-double-left`
			'fa_template'             => '%icon%',
			'before'                  => '',
			'after'                   => '',
		);
		$atts = shortcode_atts( $defaults, $atts, 'alg_back_button' );

		if ( ! empty( $atts['fa'] ) ) {
			// Font Awesome
			$atts['label'] = str_replace( '%icon%', '<i class="' . $atts['fa'] . '"></i>', $atts['fa_template'] );
			$atts['type']  = 'simple';
		} elseif (
			! empty( $atts['not_lang_text'] ) && ! empty( $atts['lang'] ) &&
			( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) )
		) {
			// Language
			$atts['label'] = $atts['not_lang_text'];
		}

		return ( ( $back_button = alg_back_button( $atts['label'], $atts ) ) ? $atts['before'] . $back_button . $atts['after'] : '' );

	}
}
add_shortcode( 'alg_back_button', 'alg_back_button_shortcode' );

if ( ! function_exists( 'alg_back_button_translate_shortcode' ) ) {
	/**
	 * alg_back_button_translate_shortcode.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function alg_back_button_translate_shortcode( $atts, $content = '' ) {

		// E.g.: `[alg_back_button_translate lang="FR" lang_text="Retour" not_lang_text="Back"]`
		if ( isset( $atts['lang_text'] ) && isset( $atts['not_lang_text'] ) && ! empty( $atts['lang'] ) ) {
			return ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ?
				$atts['not_lang_text'] : $atts['lang_text'];
		}

		// E.g.: `[alg_back_button_translate lang="FR"]Retour[/alg_back_button_translate][alg_back_button_translate lang="DE"]Zurück[/alg_back_button_translate][alg_back_button_translate not_lang="FR,DE"]Back[/alg_back_button_translate]`
		return (
			( ! empty( $atts['lang'] )     && ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ) ||
			( ! empty( $atts['not_lang'] ) &&     defined( 'ICL_LANGUAGE_CODE' ) &&   in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['not_lang'] ) ) ) ) )
		) ? '' : $content;

	}
}
add_shortcode( 'alg_back_button_translate', 'alg_back_button_translate_shortcode' );
