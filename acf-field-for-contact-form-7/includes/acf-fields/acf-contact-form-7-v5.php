<?php
/**
 * ACF Field V5
 *
 * @package WordPress
 * @author KrishaWeb <support@krishaweb.com>
 */

// If check class exists or not.
if ( ! class_exists( 'ACF_Field_For_Contact_Form_7_V5' ) && class_exists( 'acf_field' ) ) {

	/**
	 * Declare class and extends to acf_field.
	 */
	class ACF_Field_For_Contact_Form_7_V5 extends acf_field {

		/**
		 * Class construct.
		 *
		 * @param array $settings  The settings.
		 */
		public function __construct( $settings ) {
			$this->name     = 'acf_cf7';
			$this->label    = __( 'Contact form 7', 'acf-field-for-contact-form-7' );
			$this->category = 'basic';
			$this->settings = $settings;
			parent::__construct();
		}

		/**
		 * Render acf fields.
		 *
		 * @param array $field  The field.
		 */
		public function render_field( $field ) {
			$contact_forms = WPCF7_ContactForm::find();
			?>
			<select name="<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>">
				<option value="0"><?php echo esc_html__( 'Select form', 'acf-field-for-contact-form-7' ); ?></option>
				<?php foreach ( $contact_forms as $form ) { ?>
					<option value='<?php echo esc_attr( $form->id() ); ?>' <?php selected( $field['value'], $form->id() ); ?>>
						<?php echo esc_html( $form->title() ); ?>
					</option>
				<?php } ?>
			</select>
			<?php
		}

		/**
		 * Loads a value.
		 *
		 * @param mixed  $value    The value.
		 * @param int    $post_id  The post identifier.
		 * @param string $field    The field.
		 *
		 * @return object|string contact form object or form HTML.
		 */
		public function load_value( $value, $post_id, $field ) {
			if ( ! is_admin() ) {
				$contact_forms = WPCF7_ContactForm::find();
				$form_id       = ! empty( $value ) ? (int) $value : 0;
				foreach ( $contact_forms as $form ) {
					if ( $form->id() === $form_id ) {
						// apply filter.
						$contect_object = apply_filters( 'acf_cf7_object', false );
						// If check filter.
						if ( $contect_object ) {
							return $form;
						} else {
							return do_shortcode( '[contact-form-7 id="' . $form->id() . '" title="' . $form->title() . ']' );
						}
					}
				}
			}
			return $value;
		}
	}
	new ACF_Field_For_Contact_Form_7_V5( $this->settings );
}
