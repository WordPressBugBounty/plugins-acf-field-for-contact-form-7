<?php
/**
 * ACF Field V3
 *
 * @package WordPress
 * @author KrishaWeb <support@krishaweb.com>
 */

// If check class exists or not.
if ( ! class_exists( 'ACF_Field_For_Contact_Form_7_V3' ) && class_exists( 'acf_Field' ) ) {

	/**
	 * Declare class and extends to acf_field.
	 */
	class ACF_Field_For_Contact_Form_7_V3 extends acf_Field {
		/**
		 * Class construct.
		 *
		 * @param array $settings  The settings.
		 */
		public function __construct( $settings ) {
			// do not delete!
			parent::__construct( $settings );
			// set name / title.
			$this->name  = 'acf_cf7';
			$this->title = __( 'Contact Form 7', 'acf-field-for-contact-form-7' );
		}

		/**
		 * Creates options.
		 *
		 * @param string $key    The key.
		 * @param array  $field  The field.
		 */
		public function create_options( $key, $field ) {
			// set default values.
			$field['multiple']      = isset( $field['multiple'] ) ? $field['multiple'] : 0;
			$field['allow_null']    = isset( $field['allow_null'] ) ? $field['allow_null'] : 0;
			$field['disable']       = isset( $field['disable'] ) ? $field['disable'] : '0';
			$field['hide_disabled'] = isset( $field['hide_disabled'] ) ? $field['hide_disabled'] : 0; ?>
			<tr class="field_option field_option_<?php echo esc_attr( $this->name ); ?>">
				<td class="label">
					<label><?php esc_html_e( 'Disabled forms:', 'acf-field-for-contact-form-7' ); ?></label>
					<p class="description"><?php esc_html_e( 'You can not select these forms', 'acf-field-for-contact-form-7' ); ?></p>
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
					$this->parent->create_field(
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
						$this->parent->create_field(
							array(
								'type'    => 'radio',
								'name'    => 'fields[' . $key . '][allow_null]',
								'value'   => $field['allow_null'],
								'choices' => array(
									'1' => 'Yes',
									'0' => 'No',
								),
								'layout'  => 'horizontal',
							)
						);
						?>
						</td>
					</tr>
					<tr class="field_option field_option_<?php echo esc_attr( $this->name ); ?>">
						<td class="label">
							<label><?php esc_html_e( 'Select multiple forms?', 'acf-field-for-contact-form-7' ); ?></label>
						</td>
						<td>
							<?php
							$this->parent->create_field(
								array(
									'type'    => 'radio',
									'name'    => 'fields[' . $key . '][multiple]',
									'value'   => $field['multiple'],
									'choices' => array(
										'1' => 'Yes',
										'0' => 'No',
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
								$this->parent->create_field(
									array(
										'type'    => 'radio',
										'name'    => 'fields[' . $key . '][hide_disabled]',
										'value'   => $field['hide_disabled'],
										'choices' => array(
											'1' => 'Yes',
											'0' => 'No',
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
		 * Save field.
		 *
		 * @param array $field  The field.
		 *
		 * @return array ( description_of_the_return_value ).
		 */
		public function pre_save_field( $field ) { // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
			// do stuff with field (mostly format options data).
			return parent::pre_save_field( $field );
		}

		/**
		 * Creates a field.
		 *
		 * @param array $field  The field.
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
					// Mark form as selected as required.
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
		 * Admin head.
		 */
		public function admin_head() {
		}

		/**
		 * Admin script.
		 */
		public function admin_print_scripts() {
		}

		/**
		 * Admin styles.
		 */
		public function admin_print_styles() {
		}

		/**
		 * Update value.
		 *
		 * @param int    $post_id  The post identifier.
		 * @param string $field    The field.
		 * @param string $value    The value.
		 */
		public function update_value( $post_id, $field, $value ) { // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
			// do stuff with value.
			// save value.
			parent::update_value( $post_id, $field, $value );
		}

		/**
		 * Gets the value.
		 *
		 * @param int    $post_id  The post identifier.
		 * @param string $field    The field.
		 *
		 * @return mixed  The value.
		 */
		public function get_value( $post_id, $field ) {
			// get value.
			$value = parent::get_value( $post_id, $field );

			// return value.
			return $value;
		}

		/**
		 * Gets the value for api.
		 *
		 * @param int    $post_id  The post identifier.
		 * @param string $field    The field.
		 *
		 * @return boolean|string  The value for api.
		 */
		public function get_value_for_api( $post_id, $field ) {
			// get value.
			$value = $this->get_value( $post_id, $field );

			if ( ! $value || 'null' === $value ) {
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
	}
	new ACF_Field_For_Contact_Form_7_V3( $this->settigns );
}