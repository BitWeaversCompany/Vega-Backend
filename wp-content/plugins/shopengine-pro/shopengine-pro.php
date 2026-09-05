<?php

use ShopEngine_Pro\Modules\Comparison\Comparison_Support;

defined('ABSPATH') || exit;

/**
 * Plugin Name: ShopEngine Pro
 * Description: The most advanced addons for Elementor with tons of widgets, layout pack and powerful custom controls.
 * Plugin URI: https://wpmet.com/plugins/shopengine
 * Author: Wpmet
 * Version: 2.7.4
 * Author URI: https://wpmet.com/
 * Text Domain: shopengine-pro
 * Domain Path: /languages
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * 
 */

update_option('__shopengine_license_key__', 'B5E0B5F8DD8689E6ACA49DD6E6E1A930');
update_option('shopengine_pro__license_status', 'valid');
wp_cache_set('shopengine_pro__license_status', 'valid');

if(!class_exists('ShopEngine_Pro')) {

	final class ShopEngine_Pro {

		/**
		 * Plugin Version
		 *
		 * @since 1.0.0
		 * @var string The plugin version.
		 */
		static function version() {
			return '2.7.4';
		}

		/**
		 * Package type
		 *
		 * @since 1.2.0
		 * @var string The plugin purchase type [pro/ free].
		 */
		static function package_type() {
			return 'pro';
		}

		/**
		 * Product ID
		 *
		 * @since 1.2.6
		 * @var string The plugin ID in our server.
		 */
		static function product_id() {
			return '115390';
		}

		/**
		 * Author Name
		 *
		 * @since 1.3.1
		 * @var string The plugin author.
		 */
		static function author_name() {
			return 'Wpmet';
		}

		/**
		 * Store Name
		 *
		 * @since 1.3.1
		 * @var string The store name: self site, envato.
		 */
		static function store_name() {
			return 'wpmet';
		}

		/**
		 * Minimum ShopEngine_Pro Version
		 *
		 * @since 1.0.0
		 * @var string Minimum ShopEngine_Pro version required to run the plugin.
		 */
		static function min_shopengine_version() {
			return '1.1.8';
		}

		/**
		 * Plugin file
		 *
		 * @since 1.0.0
		 * @var string plugin's root file.
		 */
		static function plugin_file() {
			return __FILE__;
		}

		/**
		 * Plugin url
		 *
		 * @since 1.0.0
		 * @var string plugin's root url.
		 */
		static function plugin_url() {
			return trailingslashit(plugin_dir_url(__FILE__));
		}

		/**
		 * Plugin dir
		 *
		 * @since 1.0.0
		 * @var string plugin's root directory.
		 */
		static function plugin_dir() {
			return trailingslashit(plugin_dir_path(__FILE__));
		}

		/**
		 * Plugin's widget directory.
		 *
		 * @since 1.0.0
		 * @var string widget's root directory.
		 */
		static function widget_dir() {
			return self::plugin_dir() . 'widgets/';
		}

		/**
		 * Plugin's widget url.
		 *
		 * @since 1.0.0
		 * @var string widget's root url.
		 */
		static function widget_url() {
			return self::plugin_url() . 'widgets/';
		}


		/**
		 * API url
		 *
		 * @since 1.0.0
		 * @var string for license, layout notification related functions.
		 */
		static function api_url() {
			return 'https://api.wpmet.com/public/';
		}

		/**
		 * Account url
		 *
		 * @since 1.2.6
		 * @var string for plugin update notification, user account page.
		 */
		static function account_url() {
			return 'https://account.wpmet.com';
		}

		/**
		 * Plugin's module directory.
		 *
		 * @since 1.0.0
		 * @var string module's root directory.
		 */
		static function module_dir() {
			return self::plugin_dir() . 'modules/';
		}

		/**
		 * Plugin's module url.
		 *
		 * @since 1.0.0
		 * @var string module's root url.
		 */
		static function module_url() {
			return self::plugin_url() . 'modules/';
		}


		/**
		 * Plugin's lib directory.
		 *
		 * @since 1.0.0
		 * @var string lib's root directory.
		 */
		static function lib_dir() {
			return self::plugin_dir() . 'libs/';
		}

		/**
		 * Plugin's lib url.
		 *
		 * @since 1.0.0
		 * @var string lib's root url.
		 */
		static function lib_url() {
			return self::plugin_url() . 'libs/';
		}


		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function __construct() {
			// Load the main static helper class.
			require_once self::plugin_dir() . 'libs/notice/notice.php';
			require_once self::plugin_dir() . 'util/activate-required/activate-shopengine.php';

			// Init Plugin
			$this->init();
		}

		/**
		 * Initialize the plugin
		 *
		 * Checks for basic plugin requirements, if one check fail don't continue,
		 * if all check have passed include the plugin class.
		 *
		 * Fired by `plugins_loaded` action hook.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function init() {

			// init notice class
			\Oxaim\Libs\Notice::init();

			// Check if ShopEngine is installed and activated.
			if(!class_exists('ShopEngine')) {
				
				add_action('init', function(){
					\ShopEngine_Pro\Util\Activate_Required\Activate_Shopengine::shopengine_missing();
				});
				
				return;
			}

			if(!version_compare(\ShopEngine::version(), self::min_shopengine_version(), '>=')) {

				$this->unmatched_shopengine_version();

				return;
			}

			// Declaring compatibility with custom order tables for the WooCommerce plugin.
			add_action( 'before_woocommerce_init', [ $this, 'woocommerce_custom_order_table_compatibility' ] );

			// Once we get here, We have passed all validation checks so we can safely include our plugin.

			add_filter('shopengine/core/package_type', function ($package_type) {
				return self::package_type();
			});

			add_action('shopengine/before_loaded', function () {
				// Load the Handler class, it's the core class of ShopEngine_Pro.
				require_once self::plugin_dir() . 'plugin.php';
			});

			/**
			 * add pro version support for comparison module
			 */
			 add_action('shopengine/module/comparison-module-pro-support', function () {
				$comparison_support = new Comparison_Support();
				$comparison_support->init();
		 	}, 100);
		}

		/**
		 * Compatibility with custom order tables for the WooCommerce plugin
		 * 
		 * @since 4.2.1
		 * @access public
		 * @return void
		 */
		public function woocommerce_custom_order_table_compatibility(){
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		}


		public function unmatched_shopengine_version() {

			\Oxaim\Libs\Notice::instance('shopengine-pro', 'missing-shopengine')
			                  ->set_type('error')
			                  ->set_message(esc_html__('To run properly ShopEngine Pro requires ShopEngine minimum version 2.0.0-beta', 'shopengine-pro'))
			                  ->call();
		}
	}

	add_action('plugins_loaded', function () {
		do_action('shopengine_pro/before_loaded');
		new ShopEngine_Pro();
		do_action('shopengine_pro/after_loaded');
	}, 20);

	register_activation_hook(__FILE__, 'shopengine_pro_activate_free_plugin_activation' ) ;

	if(!function_exists('shopengine_pro_activate_free_plugin_activation')) {

		/**
		 * Activation callback for the ShopEngine Free plugin.
		 * This function is triggered when the plugin is activated.
		 * You can add any setup or initialization code needed upon activation here.
		 */
		function shopengine_pro_activate_free_plugin_activation() {

			require_once plugin_dir_path(__FILE__) . 'util/activate-required/activate-shopengine.php';
			
			ob_start();
			\ShopEngine_Pro\Util\Activate_Required\Activate_Shopengine::activate_shopengine();
			ob_end_clean();
		}
	}

}
