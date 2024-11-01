<?php
/*
Plugin Name: Tricks and Tweaks for WooThemes Canvas
Plugin URI: http://dwh-uk.com/product/wordpress-plugins/tricks-and-tweaks-for-woothemes-canvas/
Description: Allows you to Tweak elements of the WooThemes Canvas Wordpress Theme.
Version: 1.0
Author: DataWareHouse UK
Author URI: http://dwh-uk.com
*/

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'includes/woo-functions.php' );
}


add_action( 'plugins_loaded', array( 'WTC_Tricks_Tweaks', 'get_instance' ) );

if ( ! class_exists( 'WTC_Tricks_Tweaks' ) ) :

class WTC_Tricks_Tweaks {

	/** @var Class instance */
	protected static $instance = null;

	/**
	 * Constructor
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		$my_theme = wp_get_theme();

		if ( $my_theme == "Canvas" OR
  		   $my_theme->get('Template') == "canvas" ) {

					add_action( 'redux/page/tricks_options/enqueue', 'dwh_load_tricks_css' );

					if ( ! function_exists( 'dwh_load_tricks_css' ) ) {
	  				function dwh_load_tricks_css () {

						wp_register_style( 'tricks-default', plugin_dir_url( __FILE__ ) . 'includes/css/tricks-default.css' );
						wp_enqueue_style( 'tricks-default' );
	  				} // End dwh_load_tricks_css()
					}


					if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/assets/ReduxCore/framework.php' ) ) {
							require_once( dirname( __FILE__ ) . '/assets/ReduxCore/framework.php' );
						}

						if ( !isset( $redux_demo ) && file_exists( dirname( __FILE__ ) . '/assets/option-config.php' ) ) {
							require_once( dirname( __FILE__ ) . '/assets/option-config.php' );
						}

					require_once ( 'tricks-main.php' );	// Plugin options
				}
			}

	/**
	 * Load translations
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wtc-tricks-tweaks', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * If the single instance hasn't been set, set it now.
	 * @return WTC_Tricks_Tweaks
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

}

endif;
