<?php

defined('ABSPATH') || exit;

$shopengine_title_word_limit = !empty($settings['shopengine_title_word_limit']) ? intval($settings['shopengine_title_word_limit']) : 10;
$shopengine_product_limit = !empty($settings['shopengine_product_limit']) ? intval($settings['shopengine_product_limit']) : 8;
$show_cart = !empty($settings['shopengine_is_cart']) && $settings['shopengine_is_cart'] === 'yes';


$args = array(
    'status' => array('wc-processing', 'wc-completed'),
    'date_query' => array(),
    'posts_per_page' => $shopengine_product_limit,
    'order' => 'DESC',
    'orderby' => 'date',
);

if (!empty($settings['shopengine_last_day']) && $settings['shopengine_last_day'] !== 'life_time') {
    $args['date_query'][] = array(
        'after' => '-' . intval($settings['shopengine_last_day']) . ' days',
        'post_type' => 'product', 
        'post_status' => 'publish',
    );
}

$order_query = new WC_Order_Query($args);
$orders = $order_query->get_orders();

/**
 * 
 * 
 * @var $default_content 
 * This array contains the content type for product 
 * 
 */ 
$default_content = [
    'image'     => 0,
    'category'  => 0,
    'title'     => 0,
    'rating'    => 0,
    'price'     => 0,
    // 'description' => 0,
    'buttons'     => 0,
];

if( empty($settings['shopengine_is_cats']) ) {
    unset($default_content['category']);
}

if( empty($settings['shopengine_is_rating']) ) {
    unset($default_content['rating']);
}

if (is_array($orders)):
    global $product;
    $copy_product = $product;

    ?>
        <div class="shopengine-best-selling-product view-<?php echo esc_attr($settings['shopengine_content_layout']) ?>" data-mode="<?php echo esc_attr($settings['shopengine_content_layout']) ?>">
        <?php 
         $item_counter = 0;
            foreach ($orders as $order) {
                foreach ($order->get_items() as $item_id => $item) {
                    $product = $item->get_product();
                    ?>
                    <div class='shopengine-single-product-item'>
                        <?php
                        foreach ($default_content as $key => $value) {
                            $function = '_product_' . $key;
                            if ($key === 'buttons' && $show_cart) {
                                ?>
                                <div class="add-to-cart-bt">
                                    <?php woocommerce_template_loop_add_to_cart(); ?>
                                </div>
                                <?php
                            } else {
                                \ShopEngine\Utils\Helper::$function($settings, $product);
                            }
                        }
                        ?>
                    </div>
                    <?php
                      $item_counter++; // Increment the counter

                      // Break out of the loop when the limit is reached
                      if ($item_counter >= $shopengine_product_limit) {
                          break 2; // Break out of both foreach loops
                      }
                }
            }
        ?>
        </div>
    <?php 
    
    $product = $copy_product;
endif;