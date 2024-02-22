<?php

/**
 * The class instantiated during the plugin's deactivation.
 *
 * @since      1.0
 */

class SimpleForm_Akismet_Deactivator {

	/**
	 * Run during plugin deactivation.
	 *
	 * @since    1.0
	 */
	 
	public static function deactivate() {

	  // Resume the admin notification
	  $settings = get_option('sform_settings');

      if ( $settings ) {
	    $settings['akismet'] = 'false';
        update_option('sform_settings', $settings); 
      }
      
      // Check if other forms have been activated
      global $wpdb; 
      $table = "{$wpdb->prefix}sform_shortcodes"; 
      if ( $result = $wpdb->get_results("SHOW TABLES LIKE '".$table."'") ) {
        $ids = $wpdb->get_col("SELECT id FROM `$table` WHERE id != '1'");	
        if ( $ids ) {
        foreach($ids as $id) { 
	     $form_settings = get_option('sform_'. $id .'_settings');
         if ( $form_settings != false ) {
	     $form_settings['akismet'] = 'false';
         update_option('sform_'.$id.'_settings', $form_settings); 
         }
        }
        }
      }
      
	}

}