<?php
/**
 * This template will overwrite the WooCommerce file: woocommerce/order/order-details.php.
 */

defined( 'ABSPATH' ) || exit;

$settings = $this->get_settings_for_display();

$order = wc_get_order($order_id); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

if(!$order_id) {
	return;
}
?>

<div class="shopengine-thankyou-thankyou">
	<?php
		$has_icon = ! empty( $settings['shopengine_order_thankyou_icon']['value'] );
		$icon_with_text = ! empty( $settings['shopengine_order_thankyou_icon_with_text'] );
		$show_order_number = ! empty( $settings['shopengine_order_thankyou_show_order_number'] );
		$top_content = isset( $settings['shopengine_order_thankyou_top_content'] ) ? $settings['shopengine_order_thankyou_top_content'] : 'order-number';
		$order_number = esc_html( $order->get_order_number() );
		$thank_you_text = apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'shopengine-pro' ), $order );
	?>

	<?php if ( ! $icon_with_text && $has_icon ) : ?>
		<div class="shopengine-thankyou-thankyou__icon">
			<?php \Elementor\Icons_Manager::render_icon( $settings['shopengine_order_thankyou_icon'], [ 'aria-hidden' => 'true' ] ); ?>
		</div>
	<?php endif; ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

        <h3>
			<?php echo esc_html__( 'ORDER', 'shopengine-pro' ) ?> #<?php echo esc_html( $order->get_order_number() ); ?>
        </h3>
        <p>
			<?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'shopengine-pro' ); ?>
        </p>

	<?php else : ?>

		<?php if ( $show_order_number && 'order-number' === $top_content ) : ?>

			<h3>
				<?php echo esc_html__( 'ORDER', 'shopengine-pro' ) ?> #<?php echo esc_html( $order_number ); ?>
			</h3>

			<?php if ( $icon_with_text && $has_icon ) : ?>
				<div class="shopengine-thankyou-with-icon-inline">
					<div class="shopengine-thankyou-thankyou__icon-inline">
						<?php \Elementor\Icons_Manager::render_icon( $settings['shopengine_order_thankyou_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</div>
					<p><?php echo wp_kses_post( $thank_you_text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?></p>
				</div>
			<?php else : ?>
				<p>
					<?php echo wp_kses_post( $thank_you_text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
				</p>
			<?php endif; ?>

		<?php elseif ( ! $show_order_number ) : ?>

			<?php if ( $icon_with_text && $has_icon ) : ?>
				<div class="shopengine-thankyou-with-icon-inline">
					<div class="shopengine-thankyou-thankyou__icon-inline">
						<?php \Elementor\Icons_Manager::render_icon( $settings['shopengine_order_thankyou_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</div>
					<p><?php echo wp_kses_post( $thank_you_text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?></p>
				</div>
			<?php else : ?>
				<p>
					<?php echo wp_kses_post( $thank_you_text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
				</p>
			<?php endif; ?>

		<?php else : ?>

			<?php if ( $icon_with_text && $has_icon ) : ?>
				<div class="shopengine-thankyou-with-icon-inline">
					<div class="shopengine-thankyou-thankyou__icon-inline">
						<?php \Elementor\Icons_Manager::render_icon( $settings['shopengine_order_thankyou_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</div>
					<p><?php echo wp_kses_post( $thank_you_text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?></p>
				</div>
			<?php else : ?>
				<p>
					<?php echo wp_kses_post( $thank_you_text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
				</p>
			<?php endif; ?>

			<?php if ( $show_order_number ) : ?>
				<h3>
					<?php echo esc_html__( 'ORDER', 'shopengine-pro' ) ?> #<?php echo esc_html( $order_number ); ?>
				</h3>
			<?php endif; ?>

		<?php endif; ?>

	<?php endif; ?>

</div>