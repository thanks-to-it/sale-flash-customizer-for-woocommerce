<?php
/**
 * Sale Flash Customizer for WooCommerce - Core Class
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Sale_Flash_Customizer_Core' ) ) :

class Alg_WC_Sale_Flash_Customizer_Core {

	/**
	 * is_wc_version_below_3_0_0.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $is_wc_version_below_3_0_0;

	/**
	 * globally_enabled.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $globally_enabled;

	/**
	 * per_product_enabled.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $per_product_enabled;

	/**
	 * per_category_enabled.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $per_category_enabled;

	/**
	 * per_tag_enabled.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $per_tag_enabled;

	/**
	 * do_hide_everywhere.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $do_hide_everywhere;

	/**
	 * do_hide_on_archives.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $do_hide_on_archives;

	/**
	 * do_hide_on_single.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $do_hide_on_single;

	/**
	 * default_html.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $default_html;

	/**
	 * global_html.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $global_html;

	/**
	 * shortcodes.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $shortcodes;

	/**
	 * product_terms_list.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $product_terms_list;

	/**
	 * product_terms_data.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $product_terms_data;

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @todo    (v1.0.0) add predefined styles
	 * @todo    (v1.0.0) templates per view (i.e., loop, single, related, homepage) & per product type (i.e., simple, variable etc.)
	 */
	function __construct() {

		add_action( 'init', array( $this, 'init' ) );

		add_filter( 'woocommerce_sale_flash', array( $this, 'customize_sale_flash' ), PHP_INT_MAX, 3 );

	}

	/**
	 * init.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function init() {

		$this->is_wc_version_below_3_0_0 = version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' );

		$this->globally_enabled     = ( 'yes' === get_option( 'alg_wc_sale_flash_customizer_global_enabled', 'no' ) );
		$this->per_product_enabled  = ( 'yes' === get_option( 'alg_wc_sale_flash_customizer_per_product_enabled', 'no' ) );
		$this->per_category_enabled = ( 'yes' === get_option( 'alg_wc_sale_flash_customizer_per_product_cat_enabled', 'no' ) );
		$this->per_tag_enabled      = ( 'yes' === get_option( 'alg_wc_sale_flash_customizer_per_product_tag_enabled', 'no' ) );

		$this->do_hide_everywhere   = ( 'yes' === get_option( 'alg_wc_sale_flash_customizer_hide_everywhere', 'no' ) );
		$this->do_hide_on_archives  = ( 'yes' === get_option( 'alg_wc_sale_flash_customizer_hide_on_archives', 'no' ) );
		$this->do_hide_on_single    = ( 'yes' === get_option( 'alg_wc_sale_flash_customizer_hide_on_single', 'no' ) );

		$this->default_html = '<span class="onsale">' . __( 'Sale!', 'sale-flash-customizer-for-woocommerce' ) . '</span>';
		$this->global_html  = get_option( 'alg_wc_sale_flash_customizer_global_html', $this->default_html );

		$this->shortcodes = require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-sale-flash-customizer-shortcodes.php';

		if ( $this->per_product_enabled ) {
			require_once plugin_dir_path( __FILE__ ) . 'settings/class-alg-wc-sale-flash-customizer-settings-per-product.php';
		}

	}

	/**
	 * get_product_id_or_variation_parent_id.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_product_id_or_variation_parent_id( $product ) {
		if ( ! $product || ! is_object( $product ) ) {
			return 0;
		}
		if ( $this->is_wc_version_below_3_0_0 ) {
			return $product->id;
		} else {
			return (
				$product->is_type( 'variation' ) ?
				$product->get_parent_id() :
				$product->get_id()
			);
		}
	}

	/**
	 * get_taxonomy_sale_flash.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function get_taxonomy_sale_flash( $product_id, $taxonomy ) {
		$product_terms = get_the_terms( $product_id, $taxonomy );
		if ( ! empty( $product_terms ) ) {

			if ( ! isset( $this->product_terms_list[ $taxonomy ] ) ) {
				$this->product_terms_list[ $taxonomy ] = get_option(
					'alg_wc_sale_flash_customizer_per_' . $taxonomy . '_list',
					array()
				);
			}

			if ( ! isset( $this->product_terms_data[ $taxonomy ] ) ) {
				$this->product_terms_data[ $taxonomy ] = get_option(
					'alg_wc_sale_flash_customizer_per_' . $taxonomy . '_html',
					array()
				);
			}

			if (
				! empty( $this->product_terms_list[ $taxonomy ] ) &&
				! empty( $this->product_terms_data[ $taxonomy ] )
			) {
				foreach ( $product_terms as $product_term ) {
					if (
						in_array(
							$product_term->term_id,
							$this->product_terms_list[ $taxonomy ]
						) &&
						isset( $this->product_terms_data[ $taxonomy ][ $product_term->term_id ] )
					) {
						return $this->product_terms_data[ $taxonomy ][ $product_term->term_id ];
					}
				}
			}

		}
		return false;
	}

	/**
	 * customize_sale_flash.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function customize_sale_flash( $sale_flash_html, $post, $product ) {
		$product_id = $this->get_product_id_or_variation_parent_id( $product );

		// Per product
		if (
			$this->per_product_enabled &&
			'yes' === get_post_meta( $product_id, '_' . 'alg_wc_sale_flash_customizer_enabled', true )
		) {
			return $this->shortcodes->do_shortcode(
				get_post_meta(
					$product_id,
					'_' . 'alg_wc_sale_flash_customizer_html',
					true
				),
				$product
			);
		}

		// Per category
		if (
			$this->per_category_enabled &&
			false !== ( $sale_flash = $this->get_taxonomy_sale_flash( $product_id, 'product_cat' ) )
		) {
			return $this->shortcodes->do_shortcode( $sale_flash, $product );
		}

		// Per tag
		if (
			$this->per_tag_enabled &&
			false !== ( $sale_flash = $this->get_taxonomy_sale_flash( $product_id, 'product_tag' ) )
		) {
			return $this->shortcodes->do_shortcode( $sale_flash, $product );
		}

		// All products
		if ( $this->globally_enabled ) {

			// Hiding
			if (
				( $this->do_hide_everywhere ) ||
				( $this->do_hide_on_archives && is_archive() ) ||
				( $this->do_hide_on_single && is_single() && get_the_ID() === $product_id )
			) {
				return '';
			}

			// Content
			return $this->shortcodes->do_shortcode( $this->global_html, $product );

		}

		// No changes
		return $sale_flash_html;
	}

}

endif;

return new Alg_WC_Sale_Flash_Customizer_Core();
