<?php
/**
 * Plugin Name: Remove SKU From Product detail page
 * Description: This simple plugin will remove wooCommerce SKUs completely from product description page.
 * Author: Prem Tiwari
 * Plugin URI: https://www.premtiwari.in/woocommerce-remove-sku/
 * Author URI: https://www.premtiwari.in
 * Version: 1.4.2
 * License: GPL2+
 * Requires at least: 3.8
 * Tested up to: 6.0
 * @category WooCommerce
 * @requires WooCommerce version 3.2.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class WC_Settings_sku: add new setting to remove SKU from product details page
 */
class WC_Settings_sku {

	public static function init() {
		add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
		add_action( 'woocommerce_settings_tabs_settings_tab_sku', __CLASS__ . '::settings_tab' );
		add_action( 'woocommerce_update_options_settings_tab_sku', __CLASS__ . '::update_settings' );
	}

	/**
	 * SKU setting tab.
	 * @param $settings_tabs
	 *
	 * @return mixed
	 */
	public static function add_settings_tab( $settings_tabs ) {
		$settings_tabs[ 'settings_tab_sku' ] = __( 'SKU Settings', 'woocommerce-sku-settings-tab' );

		return $settings_tabs;
	}

	/**
	 * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
	 * @uses woocommerce_admin_fields()
	 * @uses self::get_settings()
	 */
	public static function settings_tab() {
		woocommerce_admin_fields( self::get_settings() );
	}

	/**
	 * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
	 *
	 * @uses woocommerce_update_options()
	 * @uses self::get_settings()
	 */
	public static function update_settings() {
		woocommerce_update_options( self::get_settings() );
	}

	/**
	 * Get all the settings for this plugin for @return array Array of settings for @see woocommerce_admin_fields() function.
	 * @see woocommerce_admin_fields() function.
	 */
	public static function get_settings() {

		$settings = array(
			'section_title' => array(
				'name' => __( 'Sku Options', 'woocommerce-sku-settings-tab' ),
				'type' => 'title',
				'desc' => '',
				'id'   => 'wc_settings_tab_sku'
			),
			'title'         => array(
				'name' => __( 'Hide product sku', 'woocommerce-sku-settings-tab' ),
				'type' => 'select',

				'options' => array(
					'0' => __( 'No', 'woocommerce' ),
					'1' => __( 'Yes', 'woocommerce' ),
				),
				'desc'    => __( 'Hide product sku from products description page.', 'woocommerce-sku-settings-tab' ),
				'id'      => 'wc_settings_tab_sku'
			),

			'section_end' => array(
				'type' => 'sectionend',
				'id'   => 'wc_settings_tab_sku_section_end'
			)
		);

		return apply_filters( 'wc_settings_tab_sku_settings', $settings );
	}
}
WC_Settings_sku::init();

/**
 * Register and enqueue a custom stylesheet in the WordPress.
 */
function wpdocs_enqueue_custom_admin_style() {
	wp_enqueue_style( 'wrs_stylesheet', plugin_dir_url( __FILE__ ) . 'css/wrs-style.css', false, '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'wpdocs_enqueue_custom_admin_style' );

/**
 * Hide the sku from product description page.
 */
if ( get_option( 'wc_settings_tab_sku' ) == 1 ) {
	add_filter( 'wc_product_sku_enabled', '__return_false' );
	add_action( 'wp_enqueue_scripts', 'wpdocs_enqueue_custom_admin_style' );
}
