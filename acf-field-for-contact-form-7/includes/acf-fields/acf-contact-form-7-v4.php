<?php
/**
 * ACF Field V4
 *
 * @package WordPress
 * @author KrishaWeb <support@krishaweb.com>
 */

// If check class exists or not.
if ( ! class_exists( 'ACF_Field_For_Contact_Form_7_V4' ) && class_exists( 'acf_field' ) ) {

	/**
	 * Declare class and extends to acf_field.
	 */
	class ACF_Field_For_Contact_Form_7_V4 extends acf_field {
		/**
		 * Class construct.
		 */
		public function __construct() {
			$this->name  = 'acf_cf7';
			$this->label = __( 'Contact Form 7', 'acf-field-for-contact-form-7' );
			// do not delete!
			parent::__construct();
		}

		/**
		 * Loads a value.
		 *
		 * @param string $value    The value.
		 * @param int    $post_id  The post identifier.
		 * @param string $field    The field.
		 *
		 * @return string field value.
		 */
		public function load_value( $value, $post_id, $field ) {
			return $value;
		}

		/**
		 * Update value.
		 *
		 * @param string $value   The value.
		 * @param string $field   The field.
		 * @param int    $post_id The post identifier.
		 *
		 * @return string field value.
		 */
		public function update_value( $value, $field, $post_id ) {
			return $value;
		}

		/**
		 * Format value.
		 *
		 * @param string $value The value.
		 * @param string $field The field.
		 *
		 * @return string field value.
		 */
		public function format_value( $value, $field ) {
			return $value;
		}

		/**
		 * Format value for  API.
		 *
		 * @param array|WP_Post $value The value.
		 * @param string        $field The field.
		 *
		 * @return boolean|string
		 */
		public function format_value_for_api( $value, $field ) {

			if ( empty( $value ) ) {
				return false;
			}
			// apply filter.
			$contact_obj = apply_filters( 'acf_cf7_object', false );
			// If there are multiple forms, construct and return an array of form markup.
			if ( is_array( $value ) ) {
				foreach ( $value as $k => $v ) {
					$form = get_post( $v );
					if ( $contact_obj ) {
						$value = $form;
					} else {
						$f           = do_shortcode( '[contact-form-7 id="' . $form->ID . '" title="' . $form->post_title . '"]' );
						$value[ $k ] = array();
						$value[ $k ] = $f;
					}
				}
			} else {
				$form = get_post( $value );
				if ( $contact_obj ) {
					$value = $form;
				} else {
					$value = do_shortcode( '[contact-form-7 id="' . $form->ID . '" title="' . $form->post_title . '"]' );
				}
			}

			return $value;
		}

		/**
		 * Loads a field.
		 *
		 * @param string $field The field.
		 *
		 * @return string field.
		 */
		public function load_field( $field ) {
			return $field;
		}

		/**
		 * Update field.
		 *
		 * @param string $field   The field.
		 * @param int    $post_id The post identifier.
		 *
		 * @return string field
		 */
		public function update_field( $field, $post_id ) {
			return $field;
		}

		/**
		 * Creates a field.
		 *
		 * @param array $field The field.
		 */
		public function create_field( $field ) {

			$field['multiple']      = isset( $field['multiple'] ) ? $field['multiple'] : false;
			$field['disable']       = isset( $field['disable'] ) ? $field['disable'] : false;
			$field['hide_disabled'] = isset( $field['hide_disabled'] ) ? $field['hide_disabled'] : false;

			// Add multiple select functionality as required.
			$multiple = '';
			if ( '1' === $field['multiple'] ) {
				$multiple       = ' multiple="multiple" size="5" ';
				$field['name'] .= '[]';
			}

			// Begin HTML select field.
			echo wp_kses(
				sprintf(
					'<select id="%1$s" class="%2$s" name="%3$s" %4$s>',
					esc_attr( $field['name'] ),
					esc_attr( $field['class'] ),
					esc_attr( $field['name'] ),
					esc_attr( $multiple )
				),
				array(
					'select' => array(
						'id'       => true,
						'class'    => true,
						'name'     => true,
						'multiple' => true,
						'size'     => true,
					),
				)
			);

			// Add null value as required.
			if ( '1' === $field['allow_null'] ) {
				echo '<option value="null"> - Select - </option>';
			}

			// Display all contact form 7 forms.
			$forms = get_posts(
				array(
					'post_type'      => 'wpcf7_contact_form',
					'orderby'        => 'id',
					'order'          => 'ASC',
					'posts_per_page' => -1,
					'numberposts'    => -1,
				)
			);
			if ( $forms ) {
				foreach ( $forms as $k => $form ) {
					$key      = $form->ID;
					$value    = $form->post_title;
					$selected = '';

					// Mark form as selected as required
					// If the value is an array (multiple select), loop through values and check if it is selected or if not a multiple select, just check normaly.
					if ( ( is_array( $field['value'] ) && in_array( $key, $field['value'], true ) ) || ( $key === $field['value'] ) ) {
						$selected = 'selected="selected"';
					}
					// Check if field is disabled.
					if ( in_array( ( $k + 1 ), $field['disable'], true ) ) {
						// Show disabled forms?
						if ( 0 === $field['hide_disabled'] ) {
							echo wp_kses(
								sprintf(
									'<option value="%1$s" %2$s disabled="disabled">%3$s</option>',
									esc_attr( strval( $key ) ),
									esc_attr( $selected ),
									esc_html( $value )
								),
								array(
									'option' => array(
										'value'    => true,
										'selected' => true,
										'disabled' => true,
									),
								)
							);
						}
					} else {
						echo wp_kses(
							sprintf(
								'<option value="%1$s" %2$s>%3$s</option>',
								esc_attr( strval( $key ) ),
								esc_attr( $selected ),
								esc_html( $value )
							),
							array(
								'option' => array(
									'value'    => true,
									'selected' => true,
								),
							)
						);
					}
				}
			}
			echo '</select>';
		}

		/**
		 * Creates options.
		 *
		 * @param array $field  The field.
		 */
		public function create_options( $field ) {
			$defaults = array(
				'multiple'      => 0,
				'allow_null'    => 0,
				'default_value' => '',
				'choices'       => '',
				'disable'       => '',
				'hide_disabled' => 0,
			);

			$field = array_merge( $defaults, $field );
			$key   = $field['name'];
			?>
			<tr class="field_option field_option_<?php echo esc_attr( $this->name ); ?>">
				<td class="label">
					<label><?php esc_html_e( 'Disabled Forms:', 'acf-field-for-contact-form-7' ); ?></label>
					<p class="description"><?php esc_html_e( 'You will not be able to select these forms', 'acf-field-for-contact-form-7' ); ?></p>
				</td>
				<td>
				<?php
				// Get form names.
				$forms      = get_posts(
					array(
						'post_type'      => 'wpcf7_contact_form',
						'orderby'        => 'id',
						'order'          => 'ASC',
						'posts_per_page' => -1,
						'numberposts'    => -1,
					)
				);
				$choices    = array();
				$choices[0] = '---';
				$k          = 1;
				foreach ( $forms as $f ) {
					$choices[ $k ] = $f->post_title;
					++$k;
				}
				do_action(
					'acf/create_field',
					array(
						'type'       => 'select',
						'name'       => 'fields[' . $key . '][disable]',
						'value'      => $field['disable'],
						'multiple'   => '1',
						'allow_null' => '0',
						'choices'    => $choices,
						'layout'     => 'horizontal',
					)
				);
				?>
				</td>
			</tr>
			<tr class="field_option field_option_<?php echo esc_attr( $this->name ); ?>">
				<td class="label">
					<label><?php esc_html_e( 'Allow Null?', 'acf-field-for-contact-form-7' ); ?></label>
				</td>
				<td>
				<?php
				do_action(
					'acf/create_field',
					array(
						'type'    => 'radio',
						'name'    => 'fields[' . $key . '][allow_null]',
						'value'   => $field['allow_null'],
						'choices' => array(
							1 => __( 'Yes', 'acf-field-for-contact-form-7' ),
							0 => __( 'No', 'acf-field-for-contact-form-7' ),
						),
						'layout'  => 'horizontal',
					)
				);
				?>
				</td>
			</tr>
			<tr class="field_option field_option_<?php echo esc_attr( $this->name ); ?>">
				<td class="label">
					<label><?php esc_html_e( 'Select Multiple?', 'acf-field-for-contact-form-7' ); ?></label>
				</td>
				<td>
				<?php
				do_action(
					'acf/create_field',
					array(
						'type'    => 'radio',
						'name'    => 'fields[' . $key . '][multiple]',
						'value'   => $field['multiple'],
						'choices' => array(
							1 => __( 'Yes', 'acf-field-for-contact-form-7' ),
							0 => __( 'No', 'acf-field-for-contact-form-7' ),
						),
						'layout'  => 'horizontal',
					)
				);
				?>
				</td>
			</tr>
			<tr class="field_option field_option_<?php echo esc_attr( $this->name ); ?>">
				<td class="label">
					<label><?php esc_html_e( 'Hide disabled forms?', 'acf-field-for-contact-form-7' ); ?></label>
				</td>
				<td>
				<?php
				do_action(
					'acf/create_field',
					array(
						'type'    => 'radio',
						'name'    => 'fields[' . $key . '][hide_disabled]',
						'value'   => $field['hide_disabled'],
						'choices' => array(
							1 => __( 'Yes', 'acf-field-for-contact-form-7' ),
							0 => __( 'No', 'acf-field-for-contact-form-7' ),
						),
						'layout'  => 'horizontal',
					)
				);
				?>
				</td>
			</tr>
			<?php
		}

		/**
		 * Enqueue script.
		 */
		public function input_admin_enqueue_scripts() {
		}

		/**
		 * Admin head.
		 */
		public function input_admin_head() {
		}

		/**
		 * Admin enqueue scripts.
		 */
		public function field_group_admin_enqueue_scripts() {
		}

		/**
		 * Admin head.
		 */
		public function field_group_admin_head() {
		}
	}
	new ACF_Field_For_Contact_Form_7_V4();
}
