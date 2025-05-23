<?php
/**
 * Sale Flash Customizer for WooCommerce - Settings
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Settings_Sale_Flash_Customizer' ) ) :

class Alg_WC_Settings_Sale_Flash_Customizer extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function __construct() {

		$this->id    = 'alg_wc_sale_flash_customizer';
		$this->label = __( 'Sale Flash Customizer', 'sale-flash-customizer-for-woocommerce' );
		parent::__construct();

		// Sections
		require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-sale-flash-customizer-settings-section.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-sale-flash-customizer-settings-general.php';

	}

	/**
	 * get_settings.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function get_settings() {
		global $current_section;
		return array_merge(
			apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ),
			array(
				array(
					'title'     => __( 'Reset Settings', 'sale-flash-customizer-for-woocommerce' ),
					'type'      => 'title',
					'id'        => $this->id . '_' . $current_section . '_reset_options',
				),
				array(
					'title'     => __( 'Reset section settings', 'sale-flash-customizer-for-woocommerce' ),
					'desc'      => '<strong>' . __( 'Reset', 'sale-flash-customizer-for-woocommerce' ) . '</strong>',
					'desc_tip'  => __( 'Check the box and save changes to reset.', 'sale-flash-customizer-for-woocommerce' ),
					'id'        => $this->id . '_' . $current_section . '_reset',
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'type'      => 'sectionend',
					'id'        => $this->id . '_' . $current_section . '_reset_options',
				),
			)
		);
	}

	/**
	 * maybe_reset_settings.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function maybe_reset_settings() {
		global $current_section;
		if ( 'yes' === get_option( $this->id . '_' . $current_section . '_reset', 'no' ) ) {
			foreach ( $this->get_settings() as $value ) {
				if ( isset( $value['id'] ) ) {
					$id = explode( '[', $value['id'] );
					delete_option( $id[0] );
				}
			}
			if ( method_exists( 'WC_Admin_Settings', 'add_message' ) ) {
				WC_Admin_Settings::add_message(
					__( 'Your settings have been reset.', 'sale-flash-customizer-for-woocommerce' )
				);
			} else {
				add_action( 'admin_notices', array( $this, 'admin_notice_settings_reset' ) );
			}
		}
	}

	/**
	 * admin_notice_settings_reset.
	 *
	 * @version 2.0.0
	 * @since   1.1.0
	 */
	function admin_notice_settings_reset() {
		echo '<div class="notice notice-warning is-dismissible"><p><strong>' .
			esc_html__( 'Your settings have been reset.', 'sale-flash-customizer-for-woocommerce' ) .
		'</strong></p></div>';
	}

	/**
	 * Save settings.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function save() {
		parent::save();
		$this->maybe_reset_settings();
	}

}

endif;

return new Alg_WC_Settings_Sale_Flash_Customizer();
