<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @since      1.0
 */

// Prevent direct access. Exit if file is not called by WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Detect core plugin
$plugin_file = 'simpleform/simpleform.php';
if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {

 if ( !is_multisite() )  {
  $settings = get_option('sform_settings');
  if ( $settings ) {
  if ( isset( $settings['deletion_data'] ) && esc_attr($settings['deletion_data']) == 'true' ) {
  global $wpdb;
  $prefix = $wpdb->prefix;
  $submissions_table = $prefix . 'sform_submissions';
  $wpdb->query("ALTER TABLE $submissions_table DROP COLUMN spam_parameters");
  $sform_addon_settings = array( 'akismet' => $settings['akismet'], 'akismet_action' => $settings['akismet_action'], 'spam_mark' => $settings['spam_mark'], 'akismet_error' => $settings['akismet_error'] );
  $sform_core_settings = array_diff_key($settings,$sform_addon_settings);
  update_option('sform_settings', $sform_core_settings); 
  $shortcodes_table = $wpdb->prefix . 'sform_shortcodes';
  $ids = $wpdb->get_col("SELECT id FROM `$shortcodes_table` WHERE id != '1'");	
  if ( $ids ) {
	foreach ( $ids as $id ) {
	$form_settings = get_option('sform_'.$id.'_settings');
    if ( $form_settings != false ) {
       $form_settings_nv = array_diff_key($form_settings,$sform_addon_settings);
       update_option('sform_'.$id.'_settings', $form_settings_nv); 
    }
    }
  }
  }
  } 
  else {
   global $wpdb;
   $prefix = $wpdb->prefix;
   $submissions_table = $prefix . 'sform_submissions';
   $wpdb->query("ALTER TABLE $submissions_table DROP COLUMN spam_parameters");   
  }  
  delete_option( 'sform_aks_db_version' );
  } 
 else {
    global $wpdb;
    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
    $original_blog_id = get_current_blog_id();
    foreach ( $blog_ids as $blog_id ) {
      switch_to_blog( $blog_id );
      $settings = get_option('sform_settings');
      if ( $settings ) {
        if ( isset( $settings['deletion_data'] ) && esc_attr($settings['deletion_data']) == 'true' ) {
        $prefix = $wpdb->prefix;
        $submissions_table = $prefix . 'sform_submissions';
        $wpdb->query("ALTER TABLE $submissions_table DROP spam_parameters");   
        $sform_addon_settings = array( 'akismet' => $settings['akismet'], 'akismet_action' => $settings['akismet_action'], 'akismet_error' => $settings['akismet_error'] );
        $sform_core_settings = array_diff_key($settings,$sform_addon_settings);
        update_option('sform_settings', $sform_core_settings);
        $shortcodes_table = $wpdb->prefix . 'sform_shortcodes';
        $ids = $wpdb->get_col("SELECT id FROM `$shortcodes_table` WHERE id != '1'");	
        if ( $ids ) {
	    foreach ( $ids as $id ) {
	    $form_settings = get_option('sform_'.$id.'_settings');
        if ( $form_settings != false ) {
        $form_settings_nv = array_diff_key($form_settings,$sform_addon_settings);
        update_option('sform_'.$id.'_settings', $form_settings_nv); 
        }
        }
        }
        }
      }
      else {
        $prefix = $wpdb->prefix;
        $submissions_table = $prefix . 'sform_submissions';
        $wpdb->query("ALTER TABLE $submissions_table DROP spam_parameters");   
      }  
      delete_option( 'sform_aks_db_version' );
    } 
    switch_to_blog( $original_blog_id );
 }
}
else {
  global $wpdb;
  $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'sform_submissions' );
  $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'sform\_%'" );
  $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'sform\-%'" );
  $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%\_sform\_%'" );
} 
