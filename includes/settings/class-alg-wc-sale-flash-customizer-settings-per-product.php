<?php
/**
 * Sale Flash Customizer for WooCommerce - Per Product Settings
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Sale_Flash_Customizer_Per_Product_Settings' ) ) :

class Alg_WC_Sale_Flash_Customizer_Per_Product_Settings {

	/**
	 * meta_box_screen.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $meta_box_screen;

	/**
	 * meta_box_context.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $meta_box_context;

	/**
	 * meta_box_priority.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $meta_box_priority;

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		add_action( 'add_meta_boxes',    array( $this, 'add_meta_box' ) );
		add_action( 'save_post_product', array( $this, 'save_meta_box' ), PHP_INT_MAX, 2 );
	}

	/**
	 * get_meta_box_options.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function get_meta_box_options() {
		return array(
			array(
				'title'   => __( 'Enable', 'sale-flash-customizer-for-woocommerce' ),
				'name'    => 'alg_wc_sale_flash_customizer_enabled',
				'default' => 'no',
				'type'    => 'select',
				'options' => array(
					'yes' => __( 'Yes', 'sale-flash-customizer-for-woocommerce' ),
					'no'  => __( 'No', 'sale-flash-customizer-for-woocommerce' ),
				),
			),
			array(
				'title'   => __( 'HTML', 'sale-flash-customizer-for-woocommerce' ),
				'name'    => 'alg_wc_sale_flash_customizer_html',
				'default' => '<span class="onsale">' .
					__( 'Sale!', 'sale-flash-customizer-for-woocommerce' ) .
				'</span>',
				'type'    => 'textarea',
				'css'     => 'width:100%;min-height:100px;',
			),
		);
	}

	/**
	 * save_meta_box.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function save_meta_box( $post_id, $__post ) {

		// Check that we are saving with current metabox displayed.
		if ( ! isset( $_POST['alg_wc_sale_flash_customizer_save_post'] ) ) {
			return;
		}

		// Check nonce
		if (
			! isset( $_POST['_alg_wc_sale_flash_customizer_nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['_alg_wc_sale_flash_customizer_nonce'] ) ),
				'alg_wc_sale_flash_customizer_nonce'
			)
		) {
			wp_die( esc_html__( 'Invalid nonce.', 'sale-flash-customizer-for-woocommerce' ) );
		}

		// Save options
		foreach ( $this->get_meta_box_options() as $option ) {
			if ( 'title' === $option['type'] ) {
				continue;
			}
			$is_enabled = ( isset( $option['enabled'] ) && 'no' === $option['enabled'] ) ? false : true;
			if ( $is_enabled ) {
				$option_value  = (
					isset( $_POST[ $option['name'] ] ) ?
					wp_kses_post( trim( wp_unslash( $_POST[ $option['name'] ] ) ) ) : // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					$option['default']
				);
				$the_post_id   = ( $option['product_id'] ?? $post_id );
				$the_meta_name = ( $option['meta_name']  ?? '_' . $option['name'] );
				update_post_meta( $the_post_id, $the_meta_name, $option_value );
			}
		}

	}

	/**
	 * add_meta_box.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function add_meta_box() {
		$screen   = ( ! empty( $this->meta_box_screen )   ? $this->meta_box_screen   : 'product' );
		$context  = ( ! empty( $this->meta_box_context )  ? $this->meta_box_context  : 'normal' );
		$priority = ( ! empty( $this->meta_box_priority ) ? $this->meta_box_priority : 'high' );
		add_meta_box(
			'alg_wc_sale_flash_customizer_meta_box',
			__( 'Sale Flash Customizer', 'sale-flash-customizer-for-woocommerce' ),
			array( $this, 'create_meta_box' ),
			$screen,
			$context,
			$priority
		);
	}

	/**
	 * create_meta_box.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @todo    (v1.0.0) `placeholder` for textarea
	 * @todo    (v1.0.0) `class` for all types (now only for select)
	 * @todo    (v1.0.0) `show_value` for all types (now only for multiple select)
	 */
	function create_meta_box() {
		$current_post_id = get_the_ID();

		$html = '';

		$html .= '<table class="widefat striped">';

		foreach ( $this->get_meta_box_options() as $option ) {

			$is_enabled = ( isset( $option['enabled'] ) && 'no' === $option['enabled'] ) ? false : true;
			if ( $is_enabled ) {

				if ( 'title' === $option['type'] ) {

					$html .= '<tr>';
					$html .= '<th colspan="3" style="' . ( $option['css'] ?? 'text-align:left;font-weight:bold;' ) . '">' .
						$option['title'] .
					'</th>';
					$html .= '</tr>';

				} else {

					$custom_attributes = '';
					$the_post_id   = ( $option['product_id'] ?? $current_post_id );
					$the_meta_name = ( $option['meta_name'] ?? '_' . $option['name'] );
					if ( get_post_meta( $the_post_id, $the_meta_name ) ) {
						$option_value = get_post_meta( $the_post_id, $the_meta_name, true );
					} else {
						$option_value = ( $option['default'] ?? '' );
					}
					$css          = ( $option['css'] ?? '' );
					$class        = ( $option['class'] ?? '' );
					$show_value   = ( isset( $option['show_value'] ) && $option['show_value'] );
					$input_ending = '';

					if ( 'select' === $option['type'] ) {

						if ( isset( $option['multiple'] ) ) {
							$custom_attributes = ' multiple';
							$option_name       = $option['name'] . '[]';
						} else {
							$option_name       = $option['name'];
						}
						if ( isset( $option['custom_attributes'] ) ) {
							$custom_attributes .= ' ' . $option['custom_attributes'];
						}
						$options = '';
						foreach ( $option['options'] as $select_option_key => $select_option_value ) {
							$selected = '';
							if ( is_array( $option_value ) ) {
								foreach ( $option_value as $single_option_value ) {
									if ( '' != ( $selected = selected( $single_option_value, $select_option_key, false ) ) ) {
										break;
									}
								}
							} else {
								$selected = selected( $option_value, $select_option_key, false );
							}
							$options .= '<option value="' . $select_option_key . '" ' . $selected . '>' .
								$select_option_value .
							'</option>';
						}

					} elseif ( 'textarea' === $option['type'] ) {

						if ( '' === $css ) {
							$css = 'min-width:300px;';
						}

					} else {

						$input_ending = ' id="' . $option['name'] . '" name="' . $option['name'] . '" value="' . $option_value . '">';
						if ( isset( $option['custom_attributes'] ) ) {
							$input_ending = ' ' . $option['custom_attributes'] . $input_ending;
						}
						if ( isset( $option['placeholder'] ) ) {
							$input_ending = ' placeholder="' . $option['placeholder'] . '"' . $input_ending;
						}

					}

					switch ( $option['type'] ) {

						case 'price':
							$field_html = '<input style="' . $css . '" class="short wc_input_price" type="number" step="0.0001"' . $input_ending;
							break;

						case 'date':
							$field_html = '<input style="' . $css . '" class="input-text" display="date" type="text"' . $input_ending;
							break;

						case 'textarea':
							$field_html = '<textarea style="' . $css . '" id="' . $option['name'] . '" name="' . $option['name'] . '">' . $option_value . '</textarea>';
							break;

						case 'select':
							$field_html = '<select' . $custom_attributes . ' class="' . $class . '" style="' . $css . '" id="' . $option['name'] . '" name="' .
								$option_name . '">' . $options . '</select>' .
								(
									$show_value && ! empty( $option_value ) ?
									'<em>' .
										sprintf(
											/* Translators: %s: Option value. */
											__( 'Selected: %s.', 'sale-flash-customizer-for-woocommerce' ),
											implode( ', ', $option_value )
										) .
									'</em>' :
									''
								);
							break;

						default:
							$field_html = '<input style="' . $css . '" class="short" type="' . $option['type'] . '"' . $input_ending;

					}

					$html .= '<tr>';

					$maybe_tooltip = (
						isset( $option['tooltip'] ) && '' != $option['tooltip'] ?
						'<span style="float:right;">' .
							wc_help_tip( $option['tooltip'], true ) .
						'</span>' :
						''
					);
					$html .= '<th style="text-align:left;width:25%;font-weight:bold;">' .
						$option['title'] . $maybe_tooltip .
					'</th>';

					if ( isset( $option['desc'] ) && '' != $option['desc'] ) {
						$html .= '<td style="font-style:italic;width:25%;">' .
							$option['desc'] .
						'</td>';
					}

					$html .= '<td>' . $field_html . '</td>';

					$html .= '</tr>';

				}

			}

		}

		$html .= '</table>';

		$html .= '<input type="hidden" name="alg_wc_sale_flash_customizer_save_post" value="alg_wc_sale_flash_customizer_save_post">';

		echo wp_kses(
			$html,
			$this->get_allowed_html()
		);

		wp_nonce_field(
			'alg_wc_sale_flash_customizer_nonce',
			'_alg_wc_sale_flash_customizer_nonce'
		);

	}

	/**
	 * get_allowed_html.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_allowed_html() {
		$allowed_html = array(
			'input' => array(
				'type'     => true,
				'id'       => true,
				'name'     => true,
				'class'    => true,
				'style'    => true,
				'value'    => true,
				'checked'  => true,
			),
			'select' => array(
				'id'       => true,
				'name'     => true,
				'class'    => true,
				'style'    => true,
			),
			'option' => array(
				'value'    => true,
				'selected' => true,
			),
		);
		return array_merge(
			wp_kses_allowed_html( 'post' ),
			$allowed_html
		);
	}

}

endif;

return new Alg_WC_Sale_Flash_Customizer_Per_Product_Settings();
