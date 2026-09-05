<?php

namespace Elementor;

defined('ABSPATH') || exit;

class ShopEngine_Account_Wishlist_Config extends \ShopEngine\Base\Widget_Config
{

	public function get_name() {
		return 'account-wishlist';
	}

	public function get_title() {

		return esc_html__('My Account Wishlist', 'shopengine-pro');
	}

	public function get_icon() {
		return 'shopengine-widget-icon shopengine-icon-account_wishlist';
	}

	public function get_categories() {
		return ['shopengine-my_account'];
	}

	public function get_keywords() {
		return ['account wishlist', 'shopengine', 'account'];
	}

	public function get_template_territory() {
		return ['my_account', 'account_wishlist'];
	}
}
