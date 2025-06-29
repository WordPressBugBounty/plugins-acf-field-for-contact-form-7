<?php
/**
 * Class for ACF Field support.
 *
 * @package WordPress
 */

// If check class exists.
if ( ! class_exists( 'ACF_Field_For_Contact_Form_7' ) ) {

	/**
	 * Declare class.
	 */
	class ACF_Field_For_Contact_Form_7 {

		/**
		 * ACF Settings.
		 *
		 * @var array
		 */
		private $settings;

		/**
		 * Admin notice message.
		 *
		 * @var string
		 */
		private $message;

		/**
		 * Calling construct.
		 */
		public function __construct() {
			// Setting.
			$this->settings = array(
				'version' => '1.0',
				'url'     => plugin_dir_url( __FILE__ ),
				'path'    => plugin_dir_path( __FILE__ ),
			);
			// ACF plugin error message.
			add_action( 'init', array( $this, 'acf_cf7_error_message' ) );
			// Admin notice.
			add_action( 'admin_notices', array( $this, 'acf_cf7_check_acf_is_activate' ) );
			// Plugin meta row.
			add_filter( 'plugin_row_meta', array( $this, 'acf_cf7_add_on_plugin_row_meta' ), 10, 3 );
			// If check required plugin working OR not.
			if ( ! class_exists( 'acf' ) || ! defined( 'WPCF7_VERSION' ) ) {
				return;
			}
			// version 3.
			add_action( 'init', array( $this, 'acf_cf7_init' ) );
			// version 4+.
			add_action( 'acf/register_fields', array( $this, 'acf_cf7_register_fields' ) );
			// include field ( version 5 ).
			add_action( 'acf/include_field_types', array( $this, 'acf_cf7_include_fields' ) );
			// Admin head.
			add_action( 'admin_head', array( $this, 'acf_cf7_admin_head' ) );
			// Admin footer based on screen.
			add_action(
				'current_screen',
				function () {
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$request = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
					$screen  = get_current_screen();
					if ( is_admin() && $screen && 'acf-field-group' === $screen->post_type && 'post' === $screen->base && 'edit' === $request ) {
						// For ACF 5.0.0+.
						add_action( 'acf/field_group/admin_footer', array( $this, 'acf_cf7_admin_footer' ) );
					} else {
						// For ACF 4.4.0+.
						add_action( 'admin_footer', array( $this, 'acf_cf7_admin_footer' ) );
					}
				}
			);
		}

		/**
		 * ACF Field Init.
		 */
		public function acf_cf7_init() {
			// If function exists or not.
			if ( function_exists( 'register_field' ) ) {
				register_field( 'acf_field_cf7', $this->settings['path'] . 'acf-fields/acf-contact-form-7-v3.php' );
			}
		}

		/**
		 * ACF register fields.
		 */
		public function acf_cf7_register_fields() {
			require_once $this->settings['path'] . 'acf-fields/acf-contact-form-7-v4.php';
		}

		/**
		 * ACF5 include field.
		 *
		 * @param int $version Plugin version.
		 */
		public function acf_cf7_include_fields( $version = 5 ) {
			require_once $this->settings['path'] . 'acf-fields/acf-contact-form-7-v' . $version . '.php';
		}

		/**
		 * If check ACF plugin activate or not.
		 */
		public function acf_cf7_check_acf_is_activate() {
			if ( ! class_exists( 'acf' ) ) {
				echo '<div class="notice notice-error is-dismissible"><p>' . wp_sprintf( esc_html( $this->message ), 'ACF Field For CF 7', 'Advanced Custom Fields' ) . '</p></div>';
			} elseif ( ! defined( 'WPCF7_VERSION' ) ) {
				echo '<div class="notice notice-error is-dismissible"><p>' . wp_sprintf( esc_html( $this->message ), 'ACF Field For CF 7', 'Contact Form 7' ) . '</p></div>';
			}
		}

		/**
		 * Plugin row meta.
		 *
		 * @param  array  $links       Row items.
		 * @param  string $plugin_file File path.
		 * @param  array  $plugin_data Plugin data.
		 *
		 * @return array Plugin row action links.
		 */
		public function acf_cf7_add_on_plugin_row_meta( $links, $plugin_file, $plugin_data ) {
			// Add documentation link in plugin meta.
			if ( ( isset( $plugin_data['slug'] ) && 'acf-field-for-contact-form-7' === $plugin_data['slug'] ) ) {
				$links[] = wp_sprintf( '<a href="mailto:%1$s"><span class="dashicons dashicons-admin-users"></span> %2$s</a>', sanitize_email( 'support@krishaweb.com' ), __( 'Support', 'acf-field-for-contact-form-7' ) );
				$links[] = wp_sprintf( '<a href="%1$s"><span class="dashicons dashicons-cart"></span> %2$s</a>', esc_url( 'https://store.krishaweb.com/product/acf-field-contact-form-7-pro/' ), __( 'Premium', 'acf-field-for-contact-form-7' ) );
			}
			return $links;
		}

		/**
		 * Admin header.
		 */
		public function acf_cf7_admin_head() {
			?>
			<style>
				.acf-cf7-sidebar {
					margin-top: 450px;
				}
				.acf-cf7-sidebar .footer {
					text-align: center;
				}
			</style>
			<?php
		}

		/**
		 * Admin footer.
		 */
		public function acf_cf7_admin_footer() {
			$pro_notice = '<div class="acf-cf7-sidebar acf-box"><div class="inner"><h2>' . esc_html__( 'ACF Field For CF 7', 'acf-field-for-contact-form-7' ) . '</h2><p>' . esc_html__( 'Adds a new \'Contact Form 7\' field to the popular Advanced Custom Fields plugin.', 'acf-field-for-contact-form-7' ) . '</p><h3>' . esc_html__( 'Pro Features', 'acf-field-for-contact-form-7' ) . '</h3><ul><li><span class="dashicons dashicons-arrow-right"></span> ' . esc_html__( 'Fully compatible with Gutenberg blocks', 'acf-field-for-contact-form-7' ) . '</li><li><span class="dashicons dashicons-arrow-right"></span> ' . esc_html__( 'Supports integration with Widgets', 'acf-field-for-contact-form-7' ) . '</li><li><span class="dashicons dashicons-arrow-right"></span> ' . esc_html__( 'Supports integration with Theme customizer', 'acf-field-for-contact-form-7' ) . '</li></ul></div><div class="footer"><p><a href="https://store.krishaweb.com/product/acf-field-contact-form-7-pro/" class="button button-primary button-large" target="_blank">' . esc_html__( 'BUY PRO', 'acf-field-for-contact-form-7' ) . '</a></p></div></div>';
			?>
			<script>
				jQuery( document ).ready( function ( $ ) {
					if ( $( '.acf-columns-2 .acf-column-2' ).length > 0 ) {
						$( '.acf-columns-2 .acf-column-2' ).clone().insertAfter( '.acf-columns-2 .acf-column-2' ).html( '<?php echo wp_kses_post( $pro_notice ); ?>' );
					} else {
						$('#tmpl-acf-field-group-pro-features').after( '<?php echo wp_kses_post( $pro_notice ); ?>' );
						jQuery( '.acf-cf7-sidebar.acf-box' ).css('margin-top', '0');
					}
				} );
			</script>
			<?php
		}

		/**
		 * Error message for required plugins.
		 */
		public function acf_cf7_error_message() {
			// translators: 1: Current plugin name 2: required plugin name.
			$this->message = __( '"%1$s" needs "%2$s" to run. Please download and activate it', 'acf-field-for-contact-form-7' );
		}
	}
}
