<?php

namespace ShopEngine_Pro\Hooks;

defined('ABSPATH') || exit;

class Page_Templates {

	public function __construct() {
		add_filter('shopengine/page_templates', [$this, 'get_list'], 1);
		add_filter('shopengine_template_category_id', [$this, 'get_template_category_id']);
	}

	public function get_template_category_id($category_id) {
		if(is_product()) {
			$terms = wc_get_product_terms(get_the_ID(), 'product_cat', ['orderby' => 'parent', 'order' => 'DESC']);
			
			if(!empty($terms) && !is_wp_error($terms)) {
				// Get activated templates to check which categories have templates
				$activated_templates = \ShopEngine\Core\Builders\Action::get_activated_templates();
				$language_code = apply_filters('shopengine_language_code', 'en');
				$template_type = is_product() ? 'single' : '';
				
				// Get available template categories for current template type and language
				$available_category_ids = [];
				if(!empty($activated_templates[$template_type]['lang'][$language_code])) {
					foreach($activated_templates[$template_type]['lang'][$language_code] as $template) {
						if(!empty($template['category_id']) && $template['status']) {
							$available_category_ids[] = $template['category_id'];
						}
					}
				}
				
				// If no category-specific templates exist, return the top-level parent
				if(empty($available_category_ids)) {
					$term = $terms[0];
					while($term->parent !== 0) {
						$term = get_term($term->parent, 'product_cat');
						if(is_wp_error($term)) {
							break;
						}
					}
					return $term->term_id;
				}
				
				// Check each term from deepest child to top-level parent
				// and return the first one that has a template
				foreach($terms as $term) {
					if(in_array($term->term_id, $available_category_ids)) {
						return $term->term_id;
					}
					
					// Check parent categories up the hierarchy
					$current_term = $term;
					while($current_term->parent !== 0) {
						$parent_term = get_term($current_term->parent, 'product_cat');
						if(is_wp_error($parent_term)) {
							break;
						}
						
						if(in_array($parent_term->term_id, $available_category_ids)) {
							return $parent_term->term_id;
						}
						
						$current_term = $parent_term;
					}
				}
			}
        
    	} elseif(is_archive() && !is_shop()) {
			$category = get_queried_object();
			
			if(isset($category->term_id) && $category->taxonomy === 'product_cat') {
				// Get activated templates to check which categories have templates
				$activated_templates = \ShopEngine\Core\Builders\Action::get_activated_templates();
				$language_code = apply_filters('shopengine_language_code', 'en');
				$template_type = 'archive';
				
				// Get available template categories for archive template type and language
				$available_category_ids = [];
				if(!empty($activated_templates[$template_type]['lang'][$language_code])) {
					foreach($activated_templates[$template_type]['lang'][$language_code] as $template) {
						if(!empty($template['category_id']) && $template['status']) {
							$available_category_ids[] = $template['category_id'];
						}
					}
				}
				
				// If no category-specific templates exist, return the top-level parent
				if(empty($available_category_ids)) {
					$term = get_term($category->term_id, 'product_cat');
					if(!is_wp_error($term)) {
						while($term->parent !== 0) {
							$term = get_term($term->parent, 'product_cat');
							if(is_wp_error($term)) {
								break;
							}
						}
						return $term->term_id;
					}
					return $category->term_id;
				}
				
				// Check if current category has a template
				if(in_array($category->term_id, $available_category_ids)) {
					return $category->term_id;
				}
				
				// Check parent categories up the hierarchy
				$current_term = get_term($category->term_id, 'product_cat');
				if(!is_wp_error($current_term)) {
					while($current_term->parent !== 0) {
						$parent_term = get_term($current_term->parent, 'product_cat');
						if(is_wp_error($parent_term)) {
							break;
						}
						
						if(in_array($parent_term->term_id, $available_category_ids)) {
							return $parent_term->term_id;
						}
						
						$current_term = $parent_term;
					}
				}
			}
			
			return isset($category->term_id) ? $category->term_id : $category_id;
		}
		return $category_id;
	}

	public function get_list($list): array {

		return array_merge($list, [
			'order'            => [
				'title'   => esc_html__('Order / Thank you', 'shopengine-pro'),
				'package' => 'pro',
				'class'   => 'ShopEngine_Pro\Templates\Hooks\Thank_You',
				'opt_key' => 'order',
				'css'     => 'order',
				'url'	  => get_permalink( wc_get_page_id( 'checkout' ) )
			],
			'my_account_login' => [
				'title'   => esc_html__('My Account Login / Register', 'shopengine-pro'),
				'package' => 'pro',
				'class'   => 'ShopEngine_Pro\Templates\Hooks\Account_Login',
				'opt_key' => 'my_account_login',
				'css'     => 'account-login-register',
			],
			'my_account'       => [
				'title'   => esc_html__('Account Dashboard', 'shopengine-pro'),
				'package' => 'pro',
				'class'   => 'ShopEngine_Pro\Templates\Hooks\Account',
				'opt_key' => 'my_account',
				'css'     => 'account',
				'url'	  => get_permalink( wc_get_page_id( 'myaccount' ) )
			],
			'account_orders'       => [
				'title'   => esc_html__('My Account Orders', 'shopengine-pro'),
				'package' => 'pro',
				'class'   => 'ShopEngine_Pro\Templates\Hooks\Account_Orders',
				'opt_key' => 'account_orders',
				'css'     => 'account-orders',
				'url'	  => get_permalink( wc_get_page_id( 'myaccount' ) )
			],
			'account_downloads'    => [
				'title'   => esc_html__('My Account Downloads', 'shopengine-pro'),
				'package' => 'pro',
				'class'   => 'ShopEngine_Pro\Templates\Hooks\Account_Downloads',
				'opt_key' => 'account_downloads',
				'css'     => 'account-downloads',
				'url'	  => get_permalink( wc_get_page_id( 'myaccount' ) )
			],
			'account_orders_view'  => [
				'title'   => esc_html__('My Account Order Details', 'shopengine-pro'),
				'package' => 'pro',
				'class'   => 'ShopEngine_Pro\Templates\Hooks\Account_Orders_View',
				'opt_key' => 'account_orders_view',
				'css'     => 'account-orders-view',
				'url'	  => get_permalink( wc_get_page_id( 'myaccount' ) )
			],
			'account_edit_account' => [
				'title'   => esc_html__('My Account Details', 'shopengine-pro'),
				'package' => 'pro',
				'class'   => 'ShopEngine_Pro\Templates\Hooks\Account_Details',
				'opt_key' => 'account_edit_account',
				'css'     => 'account-details',
				'url'	  => get_permalink( wc_get_page_id( 'myaccount' ) )
			],
			'account_edit_address' => [
				'title'   => esc_html__('My Account Address', 'shopengine-pro'),
				'package' => 'pro',
				'class'   => 'ShopEngine_Pro\Templates\Hooks\Account_Address',
				'opt_key' => 'account_edit_address',
				'css'     => 'account-address',
				'url'	  => get_permalink( wc_get_page_id( 'myaccount' ) )
			],
			'account_wishlist' => [
				'title'   => esc_html__('My Account Wishlist', 'shopengine-pro'),
				'package' => 'pro',
				'class'   => 'ShopEngine_Pro\Templates\Hooks\Account_Wishlist',
				'opt_key' => 'account_wishlist',
				'css'     => 'account-wishlist',
				'url'	  => get_permalink( wc_get_page_id( 'myaccount' ) ). '/wishlist'
			],
			'lost-password'     => [
				'title'   => esc_html__('Reset Password Form', 'shopengine-pro'),
				'package' => 'pro',
				'class'   => 'ShopEngine_Pro\Templates\Hooks\Lost_Password',
				'opt_key' => 'lost-password',
				'css'     => 'lost-password',
				'url'     => get_permalink(wc_get_page_id('myaccount')),
			],
			'reset-password'     => [
				'title'   => esc_html__('New Password Form', 'shopengine-pro'),
				'package' => 'pro',
				'class'   => 'ShopEngine_Pro\Templates\Hooks\Reset_Password',
				'opt_key' => 'reset-password',
				'css'     => 'reset-password',
				'url'     => get_permalink(wc_get_page_id('myaccount')),
			],
			'empty-cart'     => [
				'title'   => esc_html__('Empty Cart', 'shopengine-pro'),
				'package' => 'pro',
				'class'   => 'ShopEngine_Pro\Templates\Hooks\Empty_Cart',
				'opt_key' => 'empty-cart',
				'css'     => 'empty-cart',
				'url'     => get_permalink(wc_get_page_id('cart')),
			],
			'checkout-order-pay' => [
				'title'   => esc_html__('Checkout Order Pay', 'shopengine-pro'),
				'package' => 'pro',
				'class'   => 'ShopEngine_Pro\Templates\Hooks\Checkout_Order_Pay',
				'opt_key' => 'checkout-order-pay',
				'css'     => 'checkout-order-pay',
				'url'     => get_permalink(wc_get_page_id('checkout')). '/order-pay',
			]
		]);
	}
}
