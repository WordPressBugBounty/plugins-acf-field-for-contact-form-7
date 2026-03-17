<?php
/**
 * ACF Field for Contact Form 7 — ACF 5/6 field type.
 *
 * @package ACF_Field_For_Contact_Form_7
 * @author  KrishaWeb <support@krishaweb.com>
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

// If check class exists or not.
if ( ! class_exists( 'ACF_Field_For_Contact_Form_7_V5' ) && class_exists( 'acf_field' ) ) {

	/**
	 * ACF 5/6 Contact Form 7 field type.
	 *
	 * Renders a dropdown of available CF7 forms and returns
	 * the rendered shortcode markup on the front-end.
	 */
	class ACF_Field_For_Contact_Form_7_V5 extends acf_field {

		/**
		 * Class construct.
		 *
		 * @param array $settings Plugin settings array.
		 */
		public function __construct( array $settings ) {
			$this->name     = 'acf_cf7';
			$this->label    = __( 'Contact Form 7', 'acf-field-for-contact-form-7' );
			$this->category = 'basic';
			$this->defaults = array(
				'allow_null' => 0,
			);
			$this->settings = $settings;

			parent::__construct();
		}

		/**
		 * Render field settings (used in the ACF field group editor).
		 *
		 * @param array $field The field settings array.
		 */
		public function render_field_settings( array $field ): void {

			// Allow Null toggle.
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Allow Null?', 'acf-field-for-contact-form-7' ),
					'instructions' => __( 'Allow an empty (null) value to be saved.', 'acf-field-for-contact-form-7' ),
					'name'         => 'allow_null',
					'type'         => 'true_false',
					'ui'           => 1,
				)
			);
		}

		/**
		 * Render the field input (dropdown) in the post editor.
		 *
		 * @param array $field The field settings and value array.
		 */
		public function render_field( array $field ): void {

			$contact_forms = $this->get_cf7_forms();
			?>
			<select name="<?php echo esc_attr( $field['name'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>">
				<option value=""><?php echo esc_html__( '— Select Form —', 'acf-field-for-contact-form-7' ); ?></option>

				<?php foreach ( $contact_forms as $form ) : ?>
					<option value="<?php echo esc_attr( (string) $form->id() ); ?>" <?php selected( $field['value'], $form->id() ); ?>>
						<?php echo esc_html( $form->title() ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<?php
		}

		/**
		 * Format the value for front-end output via get_field() / the_field().
		 *
		 * Returns the rendered CF7 shortcode markup, or the form object
		 * when the `acf_cf7_object` filter returns true.
		 *
		 * @param mixed $value   The raw field value (form ID).
		 * @param int   $post_id The post ID.
		 * @param array $field   The field settings array.
		 *
		 * @return WPCF7_ContactForm|string|null Rendered form HTML, form object, or null.
		 */
		public function format_value( $value, $post_id, $field ) {

			if ( empty( $value ) ) {
				return null;
			}

			$form_id = (int) $value;
			$form    = $this->get_cf7_form_by_id( $form_id );

			if ( ! $form ) {
				return null;
			}

			/**
			 * Filters whether to return the WPCF7_ContactForm object instead of rendered HTML.
			 *
			 * @param bool $return_object Default false — returns rendered HTML.
			 */
			$return_object = (bool) apply_filters( 'acf_cf7_object', false );

			if ( $return_object ) {
				return $form;
			}

			$shortcode = sprintf(
				'[contact-form-7 id="%d" title="%s"]',
				$form->id(),
				esc_attr( $form->title() )
			);

			return do_shortcode( $shortcode );
		}

		/**
		 * Retrieve all published Contact Form 7 forms.
		 *
		 * @return WPCF7_ContactForm[] Array of CF7 form instances.
		 */
		private function get_cf7_forms(): array {

			if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
				return array();
			}

			return WPCF7_ContactForm::find();
		}

		/**
		 * Find a specific CF7 form by its ID.
		 *
		 * @param int $form_id The CF7 form post ID.
		 *
		 * @return WPCF7_ContactForm|null The form instance or null if not found.
		 */
		private function get_cf7_form_by_id( int $form_id ): ?object {

			if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
				return null;
			}

			$form = WPCF7_ContactForm::get_instance( $form_id );

			return $form instanceof WPCF7_ContactForm ? $form : null;
		}
	}

	new ACF_Field_For_Contact_Form_7_V5( $this->settings );
}
