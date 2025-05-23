<?php
/**
 * Sale Flash Customizer for WooCommerce - Main Class
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Sale_Flash_Customizer' ) ) :

final class Alg_WC_Sale_Flash_Customizer {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = ALG_WC_SALE_FLASH_CUSTOMIZER_VERSION;

	/**
	 * core.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $core;

	/**
	 * @var   Alg_WC_Sale_Flash_Customizer The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_Sale_Flash_Customizer Instance.
	 *
	 * Ensures only one instance of Alg_WC_Sale_Flash_Customizer is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @static
	 * @return  Alg_WC_Sale_Flash_Customizer - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WC_Sale_Flash_Customizer Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @access  public
	 */
	function __construct() {

		// Check for active WooCommerce plugin
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Declare compatibility with custom order tables for WooCommerce
		add_action( 'before_woocommerce_init', array( $this, 'wc_declare_compatibility' ) );

		// Pro
		if ( 'sale-flash-customizer-for-woocommerce-pro.php' === basename( ALG_WC_SALE_FLASH_CUSTOMIZER_FILE ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'pro/class-alg-wc-sale-flash-customizer-pro.php';
		}

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}

	}

	/**
	 * localize.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	function localize() {
		load_plugin_textdomain(
			'sale-flash-customizer-for-woocommerce',
			false,
			dirname( plugin_basename( ALG_WC_SALE_FLASH_CUSTOMIZER_FILE ) ) . '/langs/'
		);
	}

	/**
	 * wc_declare_compatibility.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @see     https://developer.woocommerce.com/docs/hpos-extension-recipe-book/
	 */
	function wc_declare_compatibility() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			$files = (
				defined( 'ALG_WC_SALE_FLASH_CUSTOMIZER_FILE_FREE' ) ?
				array( ALG_WC_SALE_FLASH_CUSTOMIZER_FILE, ALG_WC_SALE_FLASH_CUSTOMIZER_FILE_FREE ) :
				array( ALG_WC_SALE_FLASH_CUSTOMIZER_FILE )
			);
			foreach ( $files as $file ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
					'custom_order_tables',
					$file,
					true
				);
			}
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function includes() {
		// Core
		$this->core = require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-sale-flash-customizer-core.php';
	}

	/**
	 * admin.
	 *
	 * @version 1.3.0
	 * @since   1.1.0
	 */
	function admin() {

		// Action links
		add_filter(
			'plugin_action_links_' . plugin_basename( ALG_WC_SALE_FLASH_CUSTOMIZER_FILE ),
			array( $this, 'action_links' )
		);

		// Settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );

		// Version update
		if ( get_option( 'alg_wc_sale_flash_customizer_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_update' ) );
		}

	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();

		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_sale_flash_customizer' ) . '">' .
			__( 'Settings', 'sale-flash-customizer-for-woocommerce' ) .
		'</a>';

		return array_merge( $custom_links, $links );
	}

	/**
	 * Add Sale Flash Customizer settings tab to WooCommerce settings.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once plugin_dir_path( __FILE__ ) . 'settings/class-alg-wc-settings-sale-flash-customizer.php';
		return $settings;
	}

	/**
	 * version_update.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function version_update() {
		update_option( 'alg_wc_sale_flash_customizer_version', $this->version );
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( ALG_WC_SALE_FLASH_CUSTOMIZER_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( ALG_WC_SALE_FLASH_CUSTOMIZER_FILE ) );
	}

}

endif;
