<?php

/**
 * The core plugin class
 *
 * @since      1.0
 */

class SimpleForm_Akismet {

	/**
	 * The loader responsible for maintaining and registering all hooks.
	 *
	 * @since    1.0
	 */
	 
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0
	 */

	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0
	 */

	protected $version;
	
	
	/**
	 * The required version of SimpleForm plugin.
	 *
	 * @since    1.0
	 */
  
    protected $version_required = '2.1.7';	

	/**
	 * The error message.
	 *
	 * @since    1.0
	 */
	 
    protected $error = null;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.0
	 */

	public function __construct() {
		
		if ( defined( 'SIMPLEFORM_AKISMET_VERSION' ) ) { $this->version = SIMPLEFORM_AKISMET_VERSION; } 
		else { $this->version = '1.1.2'; }
		$this->plugin_name = 'simpleform-akismet';
		$this->requirements_matching();
		$this->load_dependencies();
		$this->define_admin_hooks();

	}

	/**
	 * Define the controls for the plugin compatibility.
	 *
	 * @since    1.0
	 */
	 
	private function requirements_matching() {

		if ( ! $this->is_core_active() ) {
						
			global $pagenow;
			if ($pagenow == 'plugins.php') {
			$addon = '<b>'.SIMPLEFORM_AKISMET_NAME.'</b>';
			$plugin_file = 'simpleform/simpleform.php';
            if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
	          $core_plugin = '<a href="'.esc_url( 'https://wordpress.org/plugins/simpleform/' ).'" target="_blank" style="text-decoration: none;">'.__('SimpleForm', 'simpleform-akismet' ).'</a>';
		      if ( is_multisite() ) {   
			  $url = '<a href="'.network_admin_url('plugin-install.php?tab=search&type=tag&s=simpleform-addon').'" style="text-decoration: none;">'.__('WordPress Plugin Directory', 'simpleform-akismet' ).'</a>';
		      } else {
			  $url = '<a href="'.admin_url('plugin-install.php?tab=search&type=tag&s=simpleform-addon').'" style="text-decoration: none;">'.__('WordPress Plugin Directory', 'simpleform-akismet' ).'</a>';
		      } 
		       /* translators: %1$s: SimpleForm Akismet addon name, %2$s: WordPress.org core plugin link, %3$s: URL to admin page that let browsing the WordPress Plugin Directory */
	          $message =  sprintf( __( 'In order to use the %1$s plugin you need to install and activate %2$s plugin. Search it in the %3$s.', 'simpleform-akismet' ), $addon, $core_plugin, $url );
	        }
	        else {
			  $core_plugin = '<b>'.__('SimpleForm', 'simpleform-akismet' ).'</b>';
		      $message =  sprintf( __( 'In order to use the %1$s plugin you need to activate the %2$s plugin.', 'simpleform-akismet' ), $addon, $core_plugin );
	        }
			
			$this->add_error( $message);
			
			}
		}

		if ( ! $this->is_version_compatible() ) {
           $settings = get_option('sform_settings');
           $admin_notices = ! empty( $settings['admin_notices'] ) ? esc_attr($settings['admin_notices']) : 'false';	
	       if ( $admin_notices == 'false' ) {
		     global $pagenow;
			 if ( ( isset($_GET['page']) && ( 'sform-submissions' === $_GET['page'] || 'sform-editor' === $_GET['page'] || 'sform-settings' === $_GET['page'] || 'sform-entrie' === $_GET['page'] ) ) || $pagenow == 'plugins.php' ) {
			$addon = SIMPLEFORM_AKISMET_NAME;
			$version = '<b>'. $this->version_required .'</b>';
			$this->add_error( sprintf( __( '%s requires SimpleForm version %s or greater installed. Please update SimpleForm to make it work properly!', 'simpleform-akismet' ), $addon, $version) );
			}
		   }			
		}

		if ( is_a( $this->error, 'WP_Error' ) ) {
		    add_action( 'admin_notices', array( $this, 'display_error' ), 10, 0 );
			return false;
		}		

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    1.0
	 */
	 
	private function load_dependencies() {

		// The class responsible for orchestrating the actions and filters of the core plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-loader.php';

		// The class responsible for defining all actions that occur in the admin area.		 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin.php';
		
		$this->loader = new SimpleForm_Akismet_Loader();

	}

	/**
	 * Register all hooks related to the admin area functionality of the plugin.
	 *
	 * @since    1.0
	 */
	
	private function define_admin_hooks() {
		
	   $plugin_admin = new SimpleForm_Akismet_Admin( $this->get_plugin_name(), $this->get_version() );
	   
	   // Check for core plugin listed in the active plugins
	   if ( $this->is_core_active() ) {
	     // Check for core plugin updated
	     if ( $this->is_version_compatible() ) {
  		   // Register the scripts for the admin area
		   $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
           // Add Akismet Anti-Spam field in the settings page
           $this->loader->add_filter( 'akismet_settings_filter', $plugin_admin, 'akismet_settings_field' );
           // Add Akismet error message field in settings page
           $this->loader->add_filter( 'akismet_error_message', $plugin_admin, 'akismet_message_field' );
           // Add Akismet field in the settings options array
           $this->loader->add_filter( 'sform_akismet_settings_filter', $plugin_admin, 'add_array_akismet_settings' );
           // Validate Akismet related fields in Settings page
           $this->loader->add_action( 'sform_validate_akismet_settings', $plugin_admin, 'validate_akismet_fields' );
           // Validate form with Akismet when AJAX is enabled
           $this->loader->add_action( 'akismet_spam_checking', $plugin_admin, 'akismet_ajax_checking', 10, 3 );
           // Validate form with Akismet when AJAX is not enabled
           $this->loader->add_filter( 'akismet_validation', $plugin_admin, 'akismet_checking', 10, 5 );
           // Retrieve Akismet action type. Mark message as spam if Akismet validation fails and settings allow submission
           $this->loader->add_filter( 'akismet_action', $plugin_admin, 'akismet_action', 10, 4 );
           // Show error if Akismet filter is not passed and Ajax is not enabled 
           $this->loader->add_filter( 'akismet_error', $plugin_admin, 'akismet_error' );
           // Add new form data values when form is submitted 
           $this->loader->add_filter( 'sform_akismet_values', $plugin_admin, 'spam_parameters_values', 10, 4 );
           // Send to Akismet false negative report
           $this->loader->add_filter( 'akismet_submit_spam', $plugin_admin, 'submit_false_negative', 10, 2 );
           // Send to Akismet false positive report
           $this->loader->add_filter( 'akismet_submit_ham', $plugin_admin, 'submit_false_positive', 10, 2 );
	   	   // Fallback for database table updating if code that runs during plugin activation fails 
		   $this->loader->add_action( 'plugins_loaded', $plugin_admin, 'version_check' );
		   // Add action links in the plugin meta row	    
           $this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'plugin_links', 10, 2 );
         }
         else {
	       // Add plugin upgrade notification
	       $this->loader->add_action( 'in_plugin_update_message-simpleform-akismet/simpleform-akismet.php', $plugin_admin, 'upgrade_notification', 10, 2 );
		 }
	   }
	   else {
	   // Add message in the plugin meta row if core plugin is missing    
	   $this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'plugin_meta', 10, 2 );
	   // Add plugin upgrade notification
	   $this->loader->add_action( 'in_plugin_update_message-simpleform-akismet/simpleform-akismet.php', $plugin_admin, 'upgrade_notification', 10, 2 );
	   }

	}

	/**
	 * Check if the core plugin is listed in the active plugins in the WordPress database.
	 *
	 * @since    1.0
	 */

	protected function is_core_active() {

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		
		if ( is_plugin_active_for_network( 'simpleform/simpleform.php' ) ) {
        return true;
        }
        
        else {		
    		if ( in_array( 'simpleform/simpleform.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return true;
		    } 
		    else { return false; }
		}

	}

	/**
	 * Check if the core plugin is is compatible with this addon.
	 *
	 * @since    1.0
	 */

	protected function is_version_compatible() {

		if ( ! $this->is_core_active() ) {
			return true;
		}

		if ( empty( $this->version_required ) ) {
			return true;
		}

		$plugin_data = get_plugin_data( WP_PLUGIN_DIR.'/simpleform/simpleform.php');
        if ( version_compare ( $plugin_data['Version'], $this->version_required, '<') ) {
			return false;
		}

		return true;

	}

	/**
	 * Add a new error to the WP_Error object and create the object if it doesn't exist yet.
	 *
	 * @since    1.0
	 */

	public function add_error( $message ) {
		if ( ! is_object( $this->error ) || ! is_a( $this->error, 'WP_Error' ) ) {
			$this->error = new WP_Error();
		}
		$this->error->add( 'addon_error', $message );
	}

	/**
	 * Display error. Get all the error messages and display them in the admin notices.
	 *
	 * @since    1.0
	 */

	public function display_error() {
		if ( ! is_a( $this->error, 'WP_Error' ) ) {
			return;
		}
		$message = $this->error->get_error_messages(); ?>
		<div class="notice notice-error is-dismissible">
			<p>
				<?php
				if ( count( $message ) > 1 ) {
					echo '<ul>';
					foreach ( $message as $msg ) {
						echo "<li>$msg</li>";
					}
					echo '</li>';
				} else {
					echo $message[0];
				}
				?>
			</p>
		</div>
	<?php
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0
	 */
	 
	public function run() {
		
		$this->loader->run();
		
	}

	/**
	 * Retrieve the name of the plugin.
	 *
	 * @since     1.0
	 */
	 
	public function get_plugin_name() {
		
		return $this->plugin_name;
		
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0
	 */
	 
	public function get_loader() {
		
		return $this->loader;
		
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0
	 */
	 
	public function get_version() {
		
		return $this->version;
		
	}
	
	/**
	 * Check if the Akismet plugin is active, if an API key is configured and if the spam protection is enabled by marking messages.
	 *
	 * @since    1.0
	 */

	public static function is_akismet_protection() {

        $settings = get_option('sform_settings'); 
        $akismet = ! empty( $settings['akismet'] ) ? esc_attr($settings['akismet']) : 'false';
        $akismet_key = get_option('wordpress_api_key');
        $akismet_action = ! empty( $settings['akismet_action'] ) ? esc_attr($settings['akismet_action']) : 'blocked';
        
        if ( class_exists('Akismet') && method_exists('Akismet', 'get_api_key') && Akismet::get_api_key() && $akismet == 'true' && $akismet_action == 'flagged' && ! empty($akismet_key) ) {
           return true;
        }
        
        else {		
		   return false; 
		}

	}

}