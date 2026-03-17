<?php
/**
 * Main plugin bootstrap class.
 *
 * @package ACF_Field_For_Contact_Form_7
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'ACF_Field_For_Contact_Form_7' ) ) {

	/**
	 * Registers the ACF CF7 field type and handles admin notices.
	 */
	class ACF_Field_For_Contact_Form_7 {

		/**
		 * Plugin settings.
		 *
		 * @var array{version: string, url: string, path: string}
		 */
		private array $settings;

		/**
		 * Admin notice message template.
		 *
		 * @var string
		 */
		private string $message = '';

		/**
		 * Constructor — hooks everything up.
		 */
		public function __construct() {

			$this->settings = array(
				'version' => defined( 'ACF_CF7_VERSION' ) ? ACF_CF7_VERSION : '1.8',
				'url'     => plugin_dir_url( __FILE__ ),
				'path'    => plugin_dir_path( __FILE__ ),
			);

			// translators: 1: Current plugin name 2: required plugin name.
			$this->message = __( '"%1$s" requires "%2$s" to be installed and activated.', 'acf-field-for-contact-form-7' );

			// Admin notices for missing dependencies.
			add_action( 'admin_notices', array( $this, 'acf_cf7_check_acf_is_activate' ) );

			// Plugin row meta links.
			add_filter( 'plugin_row_meta', array( $this, 'acf_cf7_add_on_plugin_row_meta' ), 10, 3 );

			// Bail early if required plugins are missing.
			if ( ! class_exists( 'acf' ) || ! defined( 'WPCF7_VERSION' ) ) {
				return;
			}

			// Register ACF 5/6 field type.
			add_action( 'acf/include_field_types', array( $this, 'acf_cf7_include_fields' ) );

			// Pro upsell sidebar on ACF field group editor.
			add_action( 'acf/field_group/admin_footer', array( $this, 'acf_cf7_admin_footer' ) );
		}

		/**
		 * Include the ACF 5/6 field type class.
		 */
		public function acf_cf7_include_fields(): void {
			require_once $this->settings['path'] . 'acf-fields/acf-contact-form-7-v5.php';
		}

		/**
		 * Show an admin error notice when ACF or CF7 is not active.
		 */
		public function acf_cf7_check_acf_is_activate(): void {

			if ( ! class_exists( 'acf' ) ) {
				$this->render_dependency_notice( 'Advanced Custom Fields' );
			} elseif ( ! defined( 'WPCF7_VERSION' ) ) {
				$this->render_dependency_notice( 'Contact Form 7' );
			}
		}

		/**
		 * Render a dependency missing admin notice.
		 *
		 * @param string $plugin_name Human-readable name of the missing plugin.
		 */
		private function render_dependency_notice( string $plugin_name ): void {

			$notice = wp_sprintf(
				/* translators: 1: Current plugin name 2: Required plugin name. */
				esc_html( $this->message ),
				'ACF Field For CF7',
				$plugin_name
			);

			printf(
				'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
				wp_kses_post( $notice )
			);
		}

		/**
		 * Add support & premium links to the plugin row on Plugins page.
		 *
		 * @param array  $links       Existing row meta links.
		 * @param string $plugin_file Plugin file path.
		 * @param array  $plugin_data Plugin header data.
		 *
		 * @return array Modified row meta links.
		 */
		public function acf_cf7_add_on_plugin_row_meta( array $links, string $plugin_file, array $plugin_data ): array {

			if ( isset( $plugin_data['slug'] ) && 'acf-field-for-contact-form-7' === $plugin_data['slug'] ) {
				$links[] = wp_sprintf(
					'<a href="mailto:%1$s"><span class="dashicons dashicons-admin-users"></span> %2$s</a>',
					sanitize_email( 'support@krishaweb.com' ),
					__( 'Support', 'acf-field-for-contact-form-7' )
				);
				$links[] = wp_sprintf(
					'<a href="%1$s"><span class="dashicons dashicons-cart"></span> %2$s</a>',
					esc_url( 'https://store.krishaweb.com/product/acf-field-contact-form-7-pro/' ),
					__( 'Premium', 'acf-field-for-contact-form-7' )
				);
			}

			return $links;
		}

		/**
		 * Output inline admin CSS for the pro upsell sidebar.
		 */
		public function acf_cf7_admin_head(): void {
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
		 * Render the pro upsell sidebar on the ACF field group editor page.
		 */
		public function acf_cf7_admin_footer(): void {
			$this->acf_cf7_admin_head();

			$pro_notice  = '<div class="acf-cf7-sidebar acf-box"><div class="inner">';
			$pro_notice .= '<h2>' . esc_html__( 'ACF Field For CF7', 'acf-field-for-contact-form-7' ) . '</h2>';
			$pro_notice .= '<p>' . esc_html__( "Adds a new 'Contact Form 7' field to the popular Advanced Custom Fields plugin.", 'acf-field-for-contact-form-7' ) . '</p>';
			$pro_notice .= '<h3>' . esc_html__( 'Pro Features', 'acf-field-for-contact-form-7' ) . '</h3>';
			$pro_notice .= '<ul>';
			$pro_notice .= '<li><span class="dashicons dashicons-arrow-right"></span> ' . esc_html__( 'Fully compatible with Gutenberg blocks', 'acf-field-for-contact-form-7' ) . '</li>';
			$pro_notice .= '<li><span class="dashicons dashicons-arrow-right"></span> ' . esc_html__( 'Supports integration with Widgets', 'acf-field-for-contact-form-7' ) . '</li>';
			$pro_notice .= '<li><span class="dashicons dashicons-arrow-right"></span> ' . esc_html__( 'Supports integration with Theme Customizer', 'acf-field-for-contact-form-7' ) . '</li>';
			$pro_notice .= '<li><span class="dashicons dashicons-arrow-right"></span> ' . esc_html__( 'Select multiple CF7 forms in a single field', 'acf-field-for-contact-form-7' ) . '</li>';
			$pro_notice .= '</ul>';
			$pro_notice .= '</div><div class="footer">';
			$pro_notice .= '<p><a href="https://store.krishaweb.com/product/acf-field-contact-form-7-pro/" class="button button-primary button-large" target="_blank">';
			$pro_notice .= esc_html__( 'BUY PRO', 'acf-field-for-contact-form-7' );
			$pro_notice .= '</a></p></div></div>';
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
	}
}
