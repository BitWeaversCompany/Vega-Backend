<?php

namespace ShopEngine_Pro\Templates\Hooks;

use ShopEngine_Pro\Traits\Path_Correction;

defined('ABSPATH') || exit;

class Account_Wishlist extends Account {

	use Path_Correction;

	protected $page_type = 'account_wishlist';


	public function init(): void {}

	protected function template_include_pre_condition(): bool {
		global $wp;
    	return is_account_page() &&  isset( $wp->query_vars['wishlist'] );
    }

}
