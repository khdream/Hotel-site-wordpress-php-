<?php
/**
 * Food Recipe Blog functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Food Recipe Blog
 */
function theme_custom_setup() {
    add_theme_support( 'post-thumbnails' ); 
    add_image_size( "small_thumbnail", 250, 245, true );
    add_image_size( "medium_thumbnail", 447, 210, true );
    add_image_size( "large_thumbnail", 515, 650, true );
    add_image_size( "news_thumbnail", 182, 127, true );
    set_post_thumbnail_size( ); 
}
add_action( 'after_setup_theme', 'theme_custom_setup' );

add_action( 'back_button', 'wpse221640_back_button' );
function wpse221640_back_button() {
    if ( wp_get_referer() ) {
    $back_text = __( '&laquo; Back' );
    $button    = "\n<button id='my-back-button' class='btn button my-back-button' onclick='javascript:history.back()'>$back_text</button>";
    echo ( $button );
}
}
    ?>
