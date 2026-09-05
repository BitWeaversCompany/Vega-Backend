<?php
/**
 * This template will overwrite the WooCommerce file: woocommerce/checkout/order-receipt.php.
 */

defined('ABSPATH') || exit;

$order = wc_get_order($order_id); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

if(!$order) {
	return;
}

$order_key = isset($_GET['key']) ? wc_clean(wp_unslash($_GET['key'])) : '';

$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();

if ( ! $show_customer_details && method_exists( $order, 'get_order_key' ) ) {
	$show_customer_details = $order->get_order_key() === $order_key;
}

if($show_customer_details) {

	$inf = $order->get_order_item_totals();
	
	// Get settings
	$settings = $this->get_settings_for_display();
	$layout = isset($settings['shopengine_thankyou_order_confirm_layout']) ? $settings['shopengine_thankyou_order_confirm_layout'] : 'table';
	
	// Get display items array
	$display_items = isset($settings['shopengine_thankyou_order_confirm_display_items']) ? $settings['shopengine_thankyou_order_confirm_display_items'] : ['order_number', 'order_date', 'order_status', 'payment_method', 'order_total'];
	
	// Item visibility based on multi-select
	$show_order_number = in_array('order_number', $display_items, true);
	$show_order_date = in_array('order_date', $display_items, true);
	$show_order_status = in_array('order_status', $display_items, true);
	$show_payment_method = in_array('payment_method', $display_items, true) && !empty($inf['payment_method']);
	$show_order_total = in_array('order_total', $display_items, true) && !empty($inf['order_total']);

	?>
    <div class="shopengine-thankyou-order-confirm">

		<?php if ( 'table' === $layout ) : ?>
			<!-- TABLE LAYOUT -->
	        <table class="table table-bordered">
	            <thead>
	            <tr>
	                <th colspan="2"><?php echo esc_html__('Order Confirmation', 'shopengine-pro'); ?></th>
	            </tr>
	            </thead>
	            <tbody>
	            <?php if ( $show_order_number ) : ?>
	                <tr class="order-number">
	                    <td><?php echo esc_html__('Order number', 'shopengine-pro'); ?></td>
	                    <td>
	                        <a title="<?php esc_html_e('Order Details', 'shopengine-pro')?>" href="<?php echo esc_url($order->get_view_order_url()) ?>">#<?php echo esc_html($order->get_order_number()) ?></a>
	                    </td>
	                </tr>
	            <?php endif; ?>

	            <?php if ( $show_order_date ) : ?>
	                <tr class="order-date">
	                    <td><?php echo esc_html__('Order Date', 'shopengine-pro'); ?></td>
	                    <td><?php echo esc_html(wc_format_datetime($order->get_date_created())) ?></td>
	                </tr>
	            <?php endif; ?>

	            <?php if ( $show_order_status ) : ?>
	                <tr class="order-status">
	                    <td><?php echo esc_html__('Order status', 'shopengine-pro'); ?></td>
	                    <td><?php echo esc_html(wc_get_order_status_name($order->get_status())) ?></td>
	                </tr>
	            <?php endif; ?>

	            <?php if ( $show_payment_method ) : ?>
	                <tr class="order-method">
	                    <td><?php echo esc_html($inf['payment_method']['label']); ?></td>
	                    <td><?php echo esc_html($inf['payment_method']['value']); ?></td>
	                </tr>
	            <?php endif; ?>

	            <?php if ( $show_order_total ) : ?>
	                <tr class="order-total">
	                    <td><?php echo esc_html($inf['order_total']['label']); ?></td>
	                    <td><?php echo wp_kses_post($inf['order_total']['value']); ?></td>
	                </tr>
	            <?php endif; ?>

	            </tbody>
	        </table>

		<?php else : ?>
			<!-- GRID LAYOUT -->
			<div class="shopengine-order-confirm-grid">
				
				<?php if ( $show_order_number ) : ?>
					<div class="shopengine-order-confirm-item order-number">
						<div class="item-label"><?php echo esc_html__('Order Number', 'shopengine-pro'); ?></div>
						<div class="item-value">
							<a title="<?php esc_html_e('Order Details', 'shopengine-pro')?>" href="<?php echo esc_url($order->get_view_order_url()) ?>">#<?php echo esc_html($order->get_order_number()) ?></a>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( $show_order_date ) : ?>
					<div class="shopengine-order-confirm-item order-date">
						<div class="item-label"><?php echo esc_html__('Order Date', 'shopengine-pro'); ?></div>
						<div class="item-value"><?php echo esc_html(wc_format_datetime($order->get_date_created())) ?></div>
					</div>
				<?php endif; ?>

				<?php if ( $show_order_status ) : ?>
					<div class="shopengine-order-confirm-item order-status">
						<div class="item-label"><?php echo esc_html__('Order Status', 'shopengine-pro'); ?></div>
						<div class="item-value"><?php echo esc_html(wc_get_order_status_name($order->get_status())) ?></div>
					</div>
				<?php endif; ?>

				<?php if ( $show_payment_method ) : ?>
					<div class="shopengine-order-confirm-item order-method">
						<div class="item-label"><?php echo esc_html($inf['payment_method']['label']); ?></div>
						<div class="item-value"><?php echo esc_html($inf['payment_method']['value']); ?></div>
					</div>
				<?php endif; ?>

				<?php if ( $show_order_total ) : ?>
					<div class="shopengine-order-confirm-item order-total">
						<div class="item-label"><?php echo esc_html($inf['order_total']['label']); ?></div>
						<div class="item-value"><?php echo wp_kses_post($inf['order_total']['value']); ?></div>
					</div>
				<?php endif; ?>

			</div>
		<?php endif; ?>

    </div>
	<?php

	do_action('woocommerce_receipt_' . $order->get_payment_method(), $order->get_id());
}
