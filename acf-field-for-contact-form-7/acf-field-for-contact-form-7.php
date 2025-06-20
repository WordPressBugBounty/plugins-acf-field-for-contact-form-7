<?php
/**
 * Plugin Name:       ACF Field For CF7
 * Plugin URI:        https://wordpress.org/plugins/acf-field-for-contact-form-7/
 * Description:       Adds a new 'Contact Form 7' field to the popular Advanced Custom Fields plugin.
 * Requires at least: 5.0
 * Requires PHP:      7.4
 * Author:            KrishaWeb
 * Author URI:        https://www.krishaweb.com/
 * Text Domain:       acf-field-for-contact-form-7
 * Domain Path:       /languages
 * Version:           1.7
 * License:           GPLv3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package           ACF_Field_For_Contact_Form_7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once 'includes/class-' . basename( __FILE__ );

/**
 * Plugin textdomain.
 */
function acf_cf7_textdomain() {
	load_plugin_textdomain( 'acf-field-for-contact-form-7', false, basename( __DIR__ ) . '/languages' );
}
add_action( 'plugins_loaded', 'acf_cf7_textdomain' );

/**
 * Plugin activation.
 */
function acf_cf7_activation() {
	// Activation code here.
}
register_activation_hook( __FILE__, 'acf_cf7_activation' );

/**
 * Plugin deactivation.
 */
function acf_cf7_deactivation() {
	// Deactivation code here.
}
register_deactivation_hook( __FILE__, 'acf_cf7_deactivation' );

/**
 * Initialization class.
 */
function acf_cf7_init() {
	new ACF_Field_For_Contact_Form_7();
}
add_action( 'plugins_loaded', 'acf_cf7_init' );
