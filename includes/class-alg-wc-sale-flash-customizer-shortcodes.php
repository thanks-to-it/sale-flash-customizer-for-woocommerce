<?php
/**
 * Sale Flash Customizer for WooCommerce - Shortcodes Class
 *
 * @version 2.0.0
 * @since   1.2.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Sale_Flash_Customizer_Shortcodes' ) ) :

class Alg_WC_Sale_Flash_Customizer_Shortcodes {

	/**
	 * current_product.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $current_product;

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 *
	 * @todo    (v1.2.0) `[discount]`, `[discount_percent]`, `[sale_price]`, `[regular_price]`: handle grouped products
	 */
	function __construct() {
		add_shortcode( 'alg_wc_sfc_meta',             array( $this, 'meta' ) );
		add_shortcode( 'alg_wc_sfc_regular_price',    array( $this, 'regular_price' ) );
		add_shortcode( 'alg_wc_sfc_sale_price',       array( $this, 'sale_price' ) );
		add_shortcode( 'alg_wc_sfc_discount',         array( $this, 'discount' ) );
		add_shortcode( 'alg_wc_sfc_discount_percent', array( $this, 'discount_percent' ) );
		add_shortcode( 'alg_wc_sfc_translate',        array( $this, 'language' ) );
	}

	/**
	 * output.
	 *
	 * @version 2.0.0
	 * @since   1.2.0
	 */
	function output( $value, $atts ) {
		return (
			( isset( $atts['before'] ) ? wp_kses_post( $atts['before'] ) : '' ) .
			$value .
			( isset( $atts['after'] ) ? wp_kses_post( $atts['after'] ) : '' )
		);
	}

	/**
	 * meta.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function meta( $atts, $content = '' ) {
		if ( ! isset( $atts['key'] ) ) {
			return '';
		}
		$product = (
			! isset( $this->current_product ) ?
			wc_get_product( get_the_ID() ) :
			$this->current_product
		);
		return (
			$product ?
			$this->output( get_post_meta( $product->get_id(), $atts['key'], true ), $atts ) :
			''
		);
	}

	/**
	 * regular_price.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function regular_price( $atts, $content = '' ) {
		$product = (
			! isset( $this->current_product ) ?
			wc_get_product( get_the_ID() ) :
			$this->current_product
		);
		return (
			$product && ! $product->is_type( 'grouped' ) ?
			$this->output( wc_price( $this->get_product_regular_price( $product ) ), $atts ) :
			''
		);
	}

	/**
	 * sale_price.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function sale_price( $atts, $content = '' ) {
		$product = (
			! isset( $this->current_product ) ?
			wc_get_product( get_the_ID() ) :
			$this->current_product
		);
		return (
			$product && ! $product->is_type( 'grouped' ) ?
			$this->output( wc_price( $this->get_product_sale_price( $product ) ), $atts ) :
			''
		);
	}

	/**
	 * discount.
	 *
	 * @version 1.2.0
	 * @since   1.1.0
	 */
	function discount( $atts, $content = '' ) {
		$product = (
			! isset( $this->current_product ) ?
			wc_get_product( get_the_ID() ) :
			$this->current_product
		);
		return (
			$product && ! $product->is_type( 'grouped' ) ?
			$this->output( wc_price( $this->get_product_discount( $product ) ), $atts ) :
			''
		);
	}

	/**
	 * discount_percent.
	 *
	 * @version 2.0.0
	 * @since   1.1.0
	 */
	function discount_percent( $atts, $content = '' ) {
		$product = (
			! isset( $this->current_product ) ?
			wc_get_product( get_the_ID() ) :
			$this->current_product
		);
		return (
			(
				$product &&
				! $product->is_type( 'grouped' ) &&
				0 != ( $product_regular_price = $this->get_product_regular_price( $product ) )
			) ?
			$this->output(
				round(
					$this->get_product_discount( $product ) / $product_regular_price * 100,
					( $atts['precision'] ?? 2 )
				),
				$atts
			) :
			''
		);
	}

	/**
	 * language.
	 *
	 * @version 2.0.0
	 * @since   1.1.0
	 */
	function language( $atts, $content = '' ) {

		// E.g.: `[alg_wc_sfc_translate lang="DE" lang_text="Verkauf!" not_lang_text="Sale!"]`
		if ( isset( $atts['lang_text'] ) && isset( $atts['not_lang_text'] ) && ! empty( $atts['lang'] ) ) {
			return ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ?
				wp_kses_post( $atts['not_lang_text'] ) : wp_kses_post( $atts['lang_text'] );
		}

		// E.g.: `[alg_wc_sfc_translate lang="DE"]Verkauf![/alg_wc_sfc_translate][alg_wc_sfc_translate not_lang="EN"]Sale![/alg_wc_sfc_translate]`
		return (
			( ! empty( $atts['lang'] )     && ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ) ||
			( ! empty( $atts['not_lang'] ) &&     defined( 'ICL_LANGUAGE_CODE' ) &&   in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['not_lang'] ) ) ) ) )
		) ? '' : wp_kses_post( $content );

	}

	/**
	 * get_product_discount.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_product_discount( $product ) {
		return (
			$product->is_type( 'variable' ) ?
			$product->get_variation_regular_price() - $product->get_variation_sale_price() :
			$product->get_regular_price()           - $product->get_sale_price()
		);
	}

	/**
	 * get_product_regular_price.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_product_regular_price( $product ) {
		return (
			$product->is_type( 'variable' ) ?
			$product->get_variation_regular_price() :
			$product->get_regular_price()
		);
	}

	/**
	 * get_product_sale_price.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function get_product_sale_price( $product ) {
		return (
			$product->is_type( 'variable' ) ?
			$product->get_variation_sale_price() :
			$product->get_sale_price()
		);
	}

	/**
	 * do_shortcode.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function do_shortcode( $content, $product ) {
		$this->current_product = $product;
		return do_shortcode( $content );
	}

}

endif;

return new Alg_WC_Sale_Flash_Customizer_Shortcodes();
