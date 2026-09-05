<?php

defined('ABSPATH') || exit;

\ShopEngine\Widgets\Widget_Helper::instance()->wc_template_filter();

$order = wc_get_order($order_id); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

if(!$order) {
	return;
}

$order_key = isset($_GET['key']) ? wc_clean(wp_unslash($_GET['key'])) : '';

$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id() || $order->get_order_key() === $order_key;


if($show_customer_details) {
	$show_shipping = isset($settings['shopengine_thankyou_address_details_show_shipping']) ? $settings['shopengine_thankyou_address_details_show_shipping'] : 'yes';

	if ( 'yes' !== $show_shipping ) {
		add_filter( 'woocommerce_order_needs_shipping_address', '__return_false' );
	}
	?>
    <div class="shopengine-thankyou-address-details">
		<?php
		wc_get_template('order/order-details-customer.php', ['order' => $order]);
		?>
    </div>
	<?php

	if ( 'yes' !== $show_shipping ) {
		remove_filter( 'woocommerce_order_needs_shipping_address', '__return_false' );
	}
}
