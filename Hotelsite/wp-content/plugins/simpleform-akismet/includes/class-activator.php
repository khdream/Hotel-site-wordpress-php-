<?php

/**
 * The class instantiated during the plugin activation.
 *
 * @since      1.0
 */

class SimpleForm_Akismet_Activator {

	/**
     * Run default functionality during plugin activation.
     *
     * @since    1.0
     */

    public static function activate($network_wide) {
	    
     if ( function_exists('is_multisite') && is_multisite() ) {
	  if($network_wide) {
        global $wpdb;
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
        foreach ( $blog_ids as $blog_id ) {
         switch_to_blog( $blog_id );
         self::sform_akismet_settings();
         self::change_db();
         restore_current_blog();
        }
      } else {
         self::sform_akismet_settings();
         self::change_db();
      }
     } else {
        self::sform_akismet_settings();
        self::change_db();
     }
    
    }

    /**
     * Modifies the database table.
     *
     * @since    1.0
     */
 
    public static function change_db() {

        $current_version = SIMPLEFORM_AKISMET_DB_VERSION;
        $installed_version = get_option('sform_aks_db_version');
       
        if ( $installed_version != $current_version ) {
        
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $prefix = $wpdb->prefix;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	        
          $submissions_table = $prefix . 'sform_submissions';
          $sql = "CREATE TABLE " . $submissions_table . " (
            id int(11) NOT NULL AUTO_INCREMENT,
            form int(7) NOT NULL DEFAULT '1',
            moved_from int(7) NOT NULL DEFAULT '0',
            requester_type tinytext NOT NULL,
            requester_id int(15) NOT NULL DEFAULT '0',
            name tinytext NOT NULL,
            lastname tinytext NOT NULL,
            email VARCHAR(200) NOT NULL,
            ip VARCHAR(128) NOT NULL,	
            phone VARCHAR(50) NOT NULL,
            subject tinytext NOT NULL,
            object text NOT NULL,
            date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            status tinytext NOT NULL,
            previous_status varchar(32) NOT NULL default '',
            trash_date datetime NULL,
            spam_parameters VARCHAR(2048) NOT NULL,
            notes text NULL,
            listable tinyint(1) NOT NULL DEFAULT 1,
            hidden tinyint(1) NOT NULL DEFAULT '0',
            movable tinyint(1) NOT NULL DEFAULT '0',         
            PRIMARY KEY  (id)
          ) ". $charset_collate .";";
          dbDelta($sql);
          update_option('sform_aks_db_version', $current_version);
        }
   
    }
    
    /**
     *  Specify the initial settings.
     *
     * @since    1.0
     */

    public static function sform_akismet_settings() {
       
 	   $main_settings = get_option('sform_settings');

       $new_settings = array('akismet' => 'false', 'akismet_action' => 'blocked', 'spam_mark' => '***'.__('SPAM', 'simpleform-akismet').'***', 'akismet_error' =>  __( 'There was an error trying to send your message. Please try again later!', 'simpleform-akismet' ) ); 
        
       if ( $main_settings ) {
 
 	      if ( isset($main_settings['akismet']) && in_array($main_settings['akismet'], array('true', 'false')) )
          return;

          $settings = array_merge($main_settings,$new_settings);
          update_option('sform_settings', $settings); 
       } 
       
       // Check if other forms have been activated
       global $wpdb; 
       $table = "{$wpdb->prefix}sform_shortcodes"; 
       if ( $result = $wpdb->get_results("SHOW TABLES LIKE '".$table."'") ) {
         $ids = $wpdb->get_col("SELECT id FROM `$table` WHERE id != '1'");	
         if ( $ids ) {
	      foreach ( $ids as $id ) {
	        $form_settings = get_option('sform_'.$id.'_settings');
            if ( $form_settings != false && !isset($form_settings['akismet']) && !in_array($form_settings['akismet'], array('true', 'false')) ) {
                 $settings = array_merge($form_settings,$new_settings);
                 update_option('sform_'.$id.'_settings', $settings); 
            }
          }
         }
       }
       
    }
    
    /**
     *  Create a table whenever a new blog is created in a WordPress Multisite installation.
     *
     * @since    1.0
     */

    public static function on_create_blog($params) {
       
       if ( is_plugin_active_for_network( 'simpleform-akismet/simpleform-akismet.php' ) ) {
       switch_to_blog( $params->blog_id );
       self::sform_akismet_settings();
       self::change_db();
       restore_current_blog();
       }

    }    
  
}