<?php
/**
 * Order Thank you page template
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit; 

global $wp;
$order = null;

if ( isset($wp->query_vars['order-received']) ) {
    $order_id = absint($wp->query_vars['order-received']); // The order ID
    $order    = wc_get_order( $order_id ); // The WC_Order object
}

// Remove the default thank you page content.
remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );

?>

<div class="shopengine">
    <?php

    if($order){

        do_action( 'woocommerce_before_thankyou', $order->get_id() );

        while ( have_posts() ): the_post();
            if( \ShopEngine\Core\Builders\Action::is_edit_with_gutenberg($this->prod_tpl_id) ) {
                shopengine_pro_content_render(do_shortcode( do_blocks( get_the_content(null, false, $this->prod_tpl_id)) ) );
            } else { 
                \ShopEngine\Core\Page_Templates\Hooks\Base_Content::instance()->load_content_designed_from_builder();
            }
        endwhile;

		do_action( 'woocommerce_thankyou', $order->get_id() );

    } else {
        esc_html_e( 'Order not found', 'shopengine-pro' );
    }
    
    ?>
</div>
