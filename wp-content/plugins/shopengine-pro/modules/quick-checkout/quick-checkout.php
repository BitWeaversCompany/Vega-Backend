<?php

namespace ShopEngine_Pro\Modules\Quick_Checkout;

defined('ABSPATH') || exit;

use ShopEngine\Core\Register\Module_List;
use ShopEngine_Pro\Traits\Singleton;
use WC_Product;

/**
 * Class Quick_Checkout
 *
 * Main Module Class
 *
 * @since 1.0.0
 */
class Quick_Checkout {

	use Singleton;

	public function init() {

		// Handle both logged-in and non-logged-in users
		add_action( "wp_ajax_nopriv_shopengine_direct_checkout", [$this, 'add_to_cart'] );
		add_action("wp_ajax_shopengine_direct_checkout", [$this, 'add_to_cart']);
		add_filter( 'woocommerce_loop_add_to_cart_link', [$this, 'added_direct_checkout_button'], 10, 2);

		if(!empty($_REQUEST['shopengine_quickview']) && isset($_REQUEST['nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['nonce'])),'wp_rest')) {
			// In quickview modal we will not show anything
			return;
		}

		if(isset($_GET['shopengine_quick_checkout']) && $_GET['shopengine_quick_checkout'] == 'modal-content') {
			if('yes' === get_option( 'woocommerce_cart_redirect_after_add' )){
				add_filter('woocommerce_add_to_cart_redirect', [$this, 'woocommerce_add_to_cart_redirect'], 99, 2);
			}

			//add_filter( 'woocommerce_add_to_cart_validation', [$this, 'remove_cart_item_before_add_to_cart'], 20, 3 ); // when used this filter then quick checkout popup will not appear previuously added to cart item client claim for this issue that's way we disable this filter.
			add_filter( 'wc_add_to_cart_message_html', [$this, 'remove_added_to_cart_message'] );
		}

		add_filter('shopengine/page_templates', [$this, 'add_quick_checkout_template'], 1);
		add_action('woocommerce_before_add_to_cart_button', [$this, 'print_button'], 1, 3);
		add_action( 'wp_footer', [$this, 'qc_modal_wrapper'] );

		add_action('wp_enqueue_scripts', function () {
			// Modal Stylesheet
			wp_enqueue_style( 'shopengine-modal-styles' );

			// Modal Script
			wp_enqueue_script( 'shopengine-quick-checkout', \ShopEngine_Pro::module_url() . 'quick-checkout/assets/js/script.js', ['jquery', 'shopengine-modal-script'] );
			wp_localize_script('shopengine-quick-checkout', 'shopEngineQuickCheckout', [
				'rest_nonce' => wp_create_nonce('wp_rest')
			]);
		});

		// Modal Wrapper
	}

	public function added_direct_checkout_button( string $buttons, WC_Product $product )
	{
		$settings = Module_List::instance()->get_settings( 'quick-checkout' );
		
		if ( 'redirect' === $settings['direct_checkout_status']['value'] ) {
			// echo "inside direct checkout";
			$add_to_cart_url = admin_url( 'admin-ajax.php' ) . '?action=shopengine_direct_checkout&product_id=' . esc_attr($product->get_id()) . '&nonce=' . wp_create_nonce( 'shopengine' );
			$buttons .= '<a class="shopengine_direct_checkout shopengine_qc_btn se-btn" data-checkout="redirect" href="' . esc_url($add_to_cart_url) . '"><i class="shopengine-icon-direct_checkout"></i></a>';
			
		} else if('popup' === $settings['direct_checkout_status']['value']){
			
			$buttons .= '<a class="shopengine_direct_checkout shopengine_qc_btn se-btn shopengine-quick-checkout-button" data-source-url="'. esc_url(wc_get_checkout_url()) .'" href="#"><i class="shopengine-icon-direct_checkout"></i></a>';

		} else if('yes' === $settings['direct_checkout_status']['value']) {
			$buttons .= '<a class="shopengine_direct_checkout shopengine_qc_btn se-btn shopengine-quick-checkout-button" data-source-url="'.esc_url(wc_get_checkout_url()).'" href="#"><i class="shopengine-icon-direct_checkout"></i></a>';
		}

		return $buttons;
	}

	public function add_to_cart()
	{
		if ( isset( $_GET['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'shopengine' ) ) {
			$redirect_url = wp_get_referer();
			if ( isset( $_GET['product_id'] ) ) {
				$product_id = intval( $_GET['product_id'] );
				$product    = wc_get_product( $product_id );
				if ( $product ) {
					$woocommerce = WC();
					$quantity = isset( $_GET['quantity'] ) ? intval( $_GET['quantity'] ) : 1;
					if ( $product->is_type( 'simple' ) ) {
						$add_to_cart = $woocommerce->cart->add_to_cart( $product_id, $quantity );
						if ( $add_to_cart ) {
							$redirect_url = get_permalink( wc_get_page_id( 'checkout' ) );
						} 
					} elseif ( $product->is_type( 'variable' ) ) {
						// get variation id and quantity
						$variation_id = isset( $_GET['variation_id'] ) ? intval( $_GET['variation_id'] ) : 0;
						$attributes = array();
	
						foreach ($product->get_attributes() as $attribute) {
							$attribute_name = 'attribute_' . sanitize_title($attribute->get_name());
							if (isset($_GET[$attribute_name])) {
								$attributes[$attribute_name] = sanitize_text_field($_GET[$attribute_name]);
							}
						}
	
						if ($variation_id && !empty($attributes)) {
							$add_to_cart = $woocommerce->cart->add_to_cart($product_id, $quantity, $variation_id, $attributes);
							if ($add_to_cart) {
								$redirect_url = get_permalink(wc_get_page_id('checkout'));
							}
						} else {
							wc_add_notice(__('Please select all required options.', 'shopengine-pro'), 'error');
						}
					} elseif ($product->is_type('external')) {
						$redirect_url = $product->get_product_url();
					} else {
						$redirect_url = $product->get_slug();
					}
					if (empty($redirect_url)) {
						$redirect_url = site_url();
					}
					wp_safe_redirect( $redirect_url );
				}
			}
		}
		exit;
	}

	public function woocommerce_add_to_cart_redirect($url, $adding_to_cart) {
		$url = \ShopEngine\Utils\Helper::add_to_url(
			get_permalink(wc_get_page_id('checkout')),
			['shopengine_quick_checkout' => 'modal-content']
		); 

		return $url;
	}



	public function add_quick_checkout_template($list) {

		return array_merge($list, [
			'quick_checkout'       => [
				'title'   => esc_html__('Quick Checkout', 'shopengine-pro'),
				'package' => 'pro',
				'class'   => '\ShopEngine_Pro\Modules\Quick_Checkout\Quick_Checkout',
				'opt_key' => 'quick_checkout',
				'css'     => 'quick-checkout',
			],
		]);
    }

	public function qc_modal_wrapper() {
		?>
		<div class="shopengine-quick-checkout-modal se-modal-wrapper"></div>
		<?php
	}


	public function print_button($add_to_cart_html = null, $product = null, $args = null) {

		global $product;

		$settings = Module_List::instance()->get_settings('quick-checkout');

		if( !empty($settings['button_label']['value']) ) {
			$label = shopengine_pro_translator('quick-checkout__button_label', $settings['button_label']['value']);
		} else {
			$label = esc_html__('Buy Now', 'shopengine-pro');
		}

		$html = '<input type="hidden" name="shopengine_quick_checkout" value="modal-content" />';
	
		if('redirect' === $settings['direct_checkout_status']['value']){
			$add_to_cart_url = admin_url( 'admin-ajax.php' ) . '?action=shopengine_direct_checkout&product_id=' . esc_attr($product->get_id()) . '&nonce=' . wp_create_nonce( 'shopengine' );
			$html .= '<a href="'. esc_url($add_to_cart_url).'" data-checkout-single="redirect" class="shopengine-quick-checkout-button shopengine-single-page-qc-btn">'.$label.'</a>';
		} else if('popup' === $settings['direct_checkout_status']['value']){
			$html .= '<button data-source-url="'.esc_url(wc_get_checkout_url()).'" class="shopengine-quick-checkout-button shopengine-single-page-qc-btn" type="submit">'.$label.'</button>';
		} else if('yes' === $settings['direct_checkout_status']['value']) {
			$html .= '<button data-source-url="'.esc_url(wc_get_checkout_url()).'" class="shopengine-quick-checkout-button shopengine-single-page-qc-btn" type="submit">'.$label.'</button>';
		}

		if($add_to_cart_html != null){
			return $html . $add_to_cart_html;

		} 
		
		shopengine_pro_content_render($html); 
	}

	function remove_added_to_cart_message() {
		return '';
	}

	public function remove_cart_item_before_add_to_cart( $passed, $product_id, $quantity ) {
		
		if( ! WC()->cart->is_empty()){
			WC()->cart->empty_cart();
		}

		return $passed;
	}
}
