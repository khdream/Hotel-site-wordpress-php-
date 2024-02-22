<?php

/**
 *
 * Plugin Name:  SimpleForm Akismet
 * Description:  Do you get junk emails through your form? This SimpleForm addon helps prevent spam submission. To work properly you need an Akismet API Key.
 * Version:      1.1.2
 * Author:       WPSForm Team
 * Author URI:   https://wpsform.com
 * License:      GPL-2.0+
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:  simpleform-akismet
 * Domain Path:  /languages
 *
 */

if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Plugin constants.
 *
 * @since    1.0
 */
 
define( 'SIMPLEFORM_AKISMET_NAME', 'SimpleForm Akismet' ); 
define( 'SIMPLEFORM_AKISMET_VERSION', '1.1.2' );
define( 'SIMPLEFORM_AKISMET_DB_VERSION', '1.1.2' );
define( 'SIMPLEFORM_AKISMET_BASENAME', plugin_basename( __FILE__ ) );
define( 'SIMPLEFORM_AKISMET_PATH', plugin_dir_path( __FILE__ ) );
if ( ! defined('SIMPLEFORM_VERSION_REQUIRED') ) { define( 'SFORM_VERSION_REQUIRED', '2.1.7' ); }

/**
 * The code that runs during plugin activation.
 *
 * @since    1.0
 */
 
function activate_simpleform_akismet($network_wide) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activator.php';
	SimpleForm_Akismet_Activator::activate($network_wide);
}

/** 
 * Change settings when a new site into a network is created.
 *
 * @since    1.0
 */ 

function simpleform_akismet_on_create_blog($params) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activator.php';
	SimpleForm_Akismet_Activator::on_create_blog($params);
}

add_action( 'wp_insert_site', 'simpleform_akismet_on_create_blog'); 

/**
 * The code that runs during plugin deactivation.
 *
 * @since    1.0
 */
 
function deactivate_simpleform_akismet() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-deactivator.php';
	SimpleForm_Akismet_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_simpleform_akismet' );
register_deactivation_hook( __FILE__, 'deactivate_simpleform_akismet' );

/**
 * The core plugin class.
 *
 * @since    1.0
 */
 
require plugin_dir_path( __FILE__ ) . '/includes/class-core.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0
 */
 
function run_SimpleForm_Akismet() {

	$plugin = new SimpleForm_Akismet();
	$plugin->run();

}

run_SimpleForm_Akismet();