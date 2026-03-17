<?php
/**
 * Plugin Name:       ACF Field For CF7
 * Plugin URI:        https://wordpress.org/plugins/acf-field-for-contact-form-7/
 * Description:       Adds a new 'Contact Form 7' field to the popular Advanced Custom Fields plugin.
 * Requires at least: 6.0
 * Requires PHP:      8.1
 * Requires Plugins:  advanced-custom-fields, contact-form-7
 * Author:            KrishaWeb
 * Author URI:        https://www.krishaweb.com/
 * Text Domain:       acf-field-for-contact-form-7
 * Version:           1.8
 * License:           GPLv3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package           ACF_Field_For_Contact_Form_7
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin constants.
define( 'ACF_CF7_VERSION', '1.8' );
define( 'ACF_CF7_PATH', plugin_dir_path( __FILE__ ) );
define( 'ACF_CF7_URL', plugin_dir_url( __FILE__ ) );
define( 'ACF_CF7_BASENAME', plugin_basename( __FILE__ ) );

require_once ACF_CF7_PATH . 'includes/class-acf-field-for-contact-form-7.php';

/**
 * Plugin activation callback.
 */
function acf_cf7_activation(): void {
	// Activation code here.
}
register_activation_hook( __FILE__, 'acf_cf7_activation' );

/**
 * Plugin deactivation callback.
 */
function acf_cf7_deactivation(): void {
	// Deactivation code here.
}
register_deactivation_hook( __FILE__, 'acf_cf7_deactivation' );

/**
 * Initialize the plugin on plugins_loaded.
 */
function acf_cf7_init(): void {
	new ACF_Field_For_Contact_Form_7();
}
add_action( 'plugins_loaded', 'acf_cf7_init' );
