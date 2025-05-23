<?php
/**
 * Sale Flash Customizer for WooCommerce - General Section Settings
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Sale_Flash_Customizer_Settings_General' ) ) :

class Alg_WC_Sale_Flash_Customizer_Settings_General extends Alg_WC_Sale_Flash_Customizer_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'sale-flash-customizer-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_terms.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function get_terms( $args ) {

		if ( ! is_array( $args ) ) {
			$_taxonomy = $args;
			$args = array(
				'taxonomy'   => $_taxonomy,
				'orderby'    => 'name',
				'hide_empty' => false,
			);
		}
		$_taxonomy = $args['taxonomy'];

		global $wp_version;
		if ( version_compare( $wp_version, '4.5.0', '>=' ) ) {
			$_terms = get_terms( $args );
		} else {
			unset( $args['taxonomy'] );
			$_terms = get_terms( $_taxonomy, $args ); // phpcs:ignore WordPress.WP.DeprecatedParameters.Get_termsParam2Found
		}

		$_terms_options = array();
		if ( ! empty( $_terms ) && ! is_wp_error( $_terms ) ) {
			foreach ( $_terms as $_term ) {
				$parent_name = '';
				if ( ! empty( $_term->parent ) ) {
					$parent_name = get_term( $_term->parent, $_taxonomy );
					$parent_name = (
						! empty( $parent_name ) && ! is_wp_error( $parent_name ) ?
						$parent_name->name . ' > ' :
						''
					);
				}
				$_terms_options[ $_term->term_id ] = $parent_name . $_term->name;
			}
		}

		return $_terms_options;
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function get_settings() {

		$global_settings = array(
			array(
				'title'    => __( 'All Products', 'sale-flash-customizer-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_sale_flash_customizer_global_options',
			),
			array(
				'title'    => __( 'All products', 'sale-flash-customizer-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'sale-flash-customizer-for-woocommerce' ) . '</strong>',
				'id'       => 'alg_wc_sale_flash_customizer_global_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Sale flash', 'sale-flash-customizer-for-woocommerce' ),
				'desc_tip' => __( 'You can use HTML and/or shortcodes here.', 'sale-flash-customizer-for-woocommerce' ),
				'id'       => 'alg_wc_sale_flash_customizer_global_html',
				'default'  => '<span class="onsale">' . __( 'Sale!', 'sale-flash-customizer-for-woocommerce' ) . '</span>',
				'type'     => 'textarea',
				'css'      => 'width:100%',
			),
			array(
				'title'    => __( 'Hide everywhere', 'sale-flash-customizer-for-woocommerce' ),
				'desc'     => __( 'Hide', 'sale-flash-customizer-for-woocommerce' ),
				'id'       => 'alg_wc_sale_flash_customizer_hide_everywhere',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Hide on archives (categories) only', 'sale-flash-customizer-for-woocommerce' ),
				'desc'     => __( 'Hide', 'sale-flash-customizer-for-woocommerce' ),
				'id'       => 'alg_wc_sale_flash_customizer_hide_on_archives',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Hide on single page only', 'sale-flash-customizer-for-woocommerce' ),
				'desc'     => __( 'Hide', 'sale-flash-customizer-for-woocommerce' ),
				'id'       => 'alg_wc_sale_flash_customizer_hide_on_single',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_sale_flash_customizer_global_options',
			),
		);

		$per_product_settings = array(
			array(
				'title'    => __( 'Per Product', 'sale-flash-customizer-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_sale_flash_customizer_per_product_options',
			),
			array(
				'title'    => __( 'Per product', 'sale-flash-customizer-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'sale-flash-customizer-for-woocommerce' ) . '</strong>',
				'desc_tip' => __( 'This will add meta box to each product\'s edit page.', 'sale-flash-customizer-for-woocommerce' ),
				'id'       => 'alg_wc_sale_flash_customizer_per_product_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_sale_flash_customizer_per_product_options',
			),
		);

		$per_taxonomy_settings = array();
		$product_terms['product_cat'] = $this->get_terms( 'product_cat' );
		$product_terms['product_tag'] = $this->get_terms( 'product_tag' );
		foreach ( $product_terms as $id => $_product_terms ) {
			$per_taxonomy_settings = array_merge(
				$per_taxonomy_settings,
				array(
					array(
						'title'    => (
							'product_cat' === $id ?
							__( 'Per Category', 'sale-flash-customizer-for-woocommerce' ) :
							__( 'Per Tag', 'sale-flash-customizer-for-woocommerce' )
						),
						'type'     => 'title',
						'id'       => 'alg_wc_sale_flash_customizer_per_' . $id . '_options',
					),
					array(
						'title'    => (
							'product_cat' === $id ?
							__( 'Per category', 'sale-flash-customizer-for-woocommerce' ) :
							__( 'Per tag', 'sale-flash-customizer-for-woocommerce' )
						),
						'desc'     => '<strong>' .
							__( 'Enable section', 'sale-flash-customizer-for-woocommerce' ) .
						'</strong>',
						'id'       => 'alg_wc_sale_flash_customizer_per_' . $id . '_enabled',
						'default'  => 'no',
						'type'     => 'checkbox',
					),
					array(
						'title'    => (
							'product_cat' === $id ?
							__( 'Categories', 'sale-flash-customizer-for-woocommerce' ) :
							__( 'Tags', 'sale-flash-customizer-for-woocommerce' )
						),
						'desc_tip' => __( 'Save changes after updating this option - new settings fields will appear.', 'sale-flash-customizer-for-woocommerce' ) . '</strong>',
						'id'       => 'alg_wc_sale_flash_customizer_per_' . $id . '_list',
						'default'  => array(),
						'type'     => 'multiselect',
						'class'    => 'chosen_select',
						'options'  => $_product_terms,
					),
				)
			);
			$product_term_list = get_option( 'alg_wc_sale_flash_customizer_per_' . $id . '_list', array() );
			foreach ( $product_term_list as $term_id ) {
				$per_taxonomy_settings = array_merge(
					$per_taxonomy_settings,
					array(
						array(
							'title'    => ( $_product_terms[ $term_id ] ?? __( 'N/A', 'sale-flash-customizer-for-woocommerce' ) ),
							'desc_tip' => __( 'You can use HTML and/or shortcodes here.', 'sale-flash-customizer-for-woocommerce' ),
							'id'       => "alg_wc_sale_flash_customizer_per_{$id}_html[{$term_id}]",
							'default'  => '<span class="onsale">' .
								__( 'Sale!', 'sale-flash-customizer-for-woocommerce' ) .
							'</span>',
							'type'     => 'textarea',
							'css'      => 'width:100%;',
						),
					)
				);
			}
			$per_taxonomy_settings = array_merge(
				$per_taxonomy_settings,
				array(
					array(
						'type'     => 'sectionend',
						'id'       => 'alg_wc_sale_flash_customizer_per_' . $id . '_options',
					),
				)
			);
		}

		return array_merge(
			$global_settings,
			$per_product_settings,
			$per_taxonomy_settings
		);
	}

}

endif;

return new Alg_WC_Sale_Flash_Customizer_Settings_General();
