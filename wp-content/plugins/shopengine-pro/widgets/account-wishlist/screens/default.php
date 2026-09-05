<div class="shopengine-account-wishlist">
    <?php
    $settings               = $this->get_settings_for_display();
    $action_column_position = $settings['shopengine_wishlist_button_remove_align'] ?? 'end';
    $show_stock_column      = ( $settings['shopengine_wishlist_show_stock_column'] ?? 'yes' ) === 'yes';
    $qty_field_style        = $settings['shopengine_wishlist_qty_field_style'] ?? 'native';

    static $shopengine_wishlist_atc_css_printed = false;
    if ( ! $shopengine_wishlist_atc_css_printed ) {
        $shopengine_wishlist_atc_css_printed = true;
        ?>
        <style class="shopengine-wishlist-scope-css">
        /* Undo generic theme rules (e.g. Hello Elementor table td/th, nth-child row tints). */
        .shopengine-account-wishlist table.shopengine-wishlist > thead > tr > th,
        .shopengine-account-wishlist table.shopengine-wishlist > tbody > tr > td {
            border-style: solid;
            border-width: 0;
            border-color: transparent;
            line-height: inherit;
            vertical-align: inherit;
            padding: 0;
            background-color: transparent;
        }
        /* Beat selectors like: table tbody > tr:nth-child(odd) > td (Hello Elementor). */
        .shopengine-account-wishlist table.shopengine-wishlist tbody > tr:nth-child(odd) > td,
        .shopengine-account-wishlist table.shopengine-wishlist tbody > tr:nth-child(odd) > th,
        .shopengine-account-wishlist table.shopengine-wishlist tbody > tr:nth-child(even) > td,
        .shopengine-account-wishlist table.shopengine-wishlist tbody > tr:nth-child(even) > th {
            background-color: transparent;
        }
        .shopengine-account-wishlist table.shopengine-wishlist tbody td .shopengine-wishlist-add-to-cart {
            transition-property: background-color, color, box-shadow, opacity;
            transition-duration: 0.15s;
            transition-timing-function: ease-out;
        }
        .shopengine-account-wishlist table.shopengine-wishlist tbody td .shopengine-wishlist-add-to-cart:focus:not(:focus-visible) {
            outline: none;
        }
        <?php if ( 'stepper' === $qty_field_style ) : ?>
        .shopengine-account-wishlist table.shopengine-wishlist .shopengine-wishlist-qty-stepper .shopengine-wishlist-quantity {
            -moz-appearance: textfield;
            appearance: textfield;
            padding-right: 0.5em;
            text-align: center;
        }
        .shopengine-account-wishlist table.shopengine-wishlist .shopengine-wishlist-qty-stepper .shopengine-wishlist-quantity::-webkit-outer-spin-button,
        .shopengine-account-wishlist table.shopengine-wishlist .shopengine-wishlist-qty-stepper .shopengine-wishlist-quantity::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        <?php endif; ?>
        </style>
        <?php
    }
    $product_layout         = $settings['shopengine_wishlist_product_layout'] ?? 'horizontal';
    $list                   = $list ?? [];

    $img_w_setting = $settings['shopengine_wishlist_product_image_width'] ?? [];
    $img_h_setting = $settings['shopengine_wishlist_product_image_height'] ?? [];
    $img_w         = isset( $img_w_setting['size'] ) ? absint( $img_w_setting['size'] ) : 0;
    $img_h         = isset( $img_h_setting['size'] ) ? absint( $img_h_setting['size'] ) : 0;
    if ( $img_w < 20 && $img_h < 20 ) {
        $legacy = $settings['shopengine_wishlist_product_image_size'] ?? [];
        if ( isset( $legacy['size'] ) ) {
            $legacy_sz = absint( $legacy['size'] );
            if ( $legacy_sz >= 20 ) {
                $img_w = $legacy_sz;
                $img_h = $legacy_sz;
            }
        }
    }
    if ( $img_w < 20 ) {
        $img_w = 100;
    }
    if ( $img_h < 20 ) {
        $img_h = 100;
    }
    $img_dim = [ $img_w, $img_h ];

    $product_inner_classes = 'shopengine-wishlist-product-inner shopengine-wishlist-product-layout--' . ( 'vertical' === $product_layout ? 'vertical' : 'horizontal' );
    $product_inner_style     = 'vertical' === $product_layout
        ? 'display:flex;flex-direction:column;align-items:center;'
        : 'display:flex;flex-direction:row;align-items:center;';
    ?>
    <table class="table shopengine-wishlist">
        <thead>
        <tr>
            <?php if ( 'start' === $action_column_position ) : ?>
                <th class="shopengine-wishlist-th shopengine-wishlist-th-action"><?php echo esc_html__( 'Action', 'shopengine-pro' ); ?></th>
            <?php endif; ?>
            <th class="shopengine-wishlist-th shopengine-wishlist-th-product"><?php echo esc_html__( 'Products', 'shopengine-pro' ); ?></th>
            <th class="shopengine-wishlist-th shopengine-wishlist-th-price"><?php echo esc_html__( 'Unit Price', 'shopengine-pro' ); ?></th>
            <th class="shopengine-wishlist-th shopengine-wishlist-th-quantity"><?php echo esc_html__( 'Quantity', 'shopengine-pro' ); ?></th>
            <?php if ( $show_stock_column ) : ?>
                <th class="shopengine-wishlist-th shopengine-wishlist-th-stock"><?php echo esc_html__( 'Stock Status', 'shopengine-pro' ); ?></th>
            <?php endif; ?>
            <th class="shopengine-wishlist-th shopengine-wishlist-th-add-cart"><?php echo esc_html__( 'Add to Cart', 'shopengine-pro' ); ?></th>
            <?php if ( 'start' !== $action_column_position ) : ?>
                <th class="shopengine-wishlist-th shopengine-wishlist-th-action"><?php echo esc_html__( 'Action', 'shopengine-pro' ); ?></th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php
        if ( is_array( $list ) ) :
            foreach ( $list as $key => $product_id ) {

                $product = wc_get_product( $product_id );
                if ( empty( $product ) ) {
                    continue;
                }

                $stock_modifier = 'instock';
                $stock_display  = '';

                if ( ! $product->is_in_stock() ) {
                    $stock_modifier = 'outofstock';
                    $stock_display  = esc_html__( 'Out of stock', 'shopengine-pro' );
                } else {
                    if ( $product->managing_stock() && null !== $product->get_stock_quantity() ) {
                        $qty_num = (int) $product->get_stock_quantity();
                        $low_amt = method_exists( $product, 'get_low_stock_amount' )
                            ? (int) $product->get_low_stock_amount()
                            : (int) get_option( 'woocommerce_notify_low_stock_amount', 2 );
                        if ( $low_amt > 0 && $qty_num > 0 && $qty_num <= $low_amt ) {
                            $stock_modifier = 'low';
                            $stock_display  = esc_html__( 'Low stock', 'shopengine-pro' );
                        }
                    }
                    if ( '' === $stock_display && $product->is_on_backorder() ) {
                        $stock_modifier = 'onbackorder';
                        $stock_display  = esc_html__( 'On backorder', 'shopengine-pro' );
                    }
                    if ( '' === $stock_display ) {
                        $stock_modifier = 'instock';
                        $stock_display  = esc_html__( 'In Stock', 'shopengine-pro' );
                    }
                }
                ?>
            <tr>
                <?php if ( 'start' === $action_column_position ) : ?>
                    <td class="shopengine-wishlist-td shopengine-wishlist-td-action product-remove">
                        <span class="shopengine-remove-action remove-badge remove-from-wishlist" data-pid="<?php echo absint( $product_id ); ?>" role="button" tabindex="0"><?php \Elementor\Icons_Manager::render_icon( $settings['shopengine_wishlist_button_remove_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
                    </td>
                <?php endif; ?>
                <td class="shopengine-wishlist-td shopengine-wishlist-td-product">
                    <div class="<?php echo esc_attr( $product_inner_classes ); ?>" style="<?php echo esc_attr( $product_inner_style ); ?>">
                    <span class="shopengine-wishlist-product-image">
                        <?php echo wp_kses_post( wp_get_attachment_image( $product->get_image_id(), $img_dim, false, [ 'width' => (string) $img_w, 'height' => (string) $img_h ] ) ); ?>
                    </span>
                    <p class="wishlist-product-name">
                        <a title="<?php esc_attr_e( 'View Product Full Details', 'shopengine-pro' ); ?>" target="_blank" href="<?php echo esc_url( $product->get_permalink() ); ?>">
                            <?php echo esc_html( $product->get_name() ); ?>
                        </a>
                    </p>
                    </div>
                </td>

                <td class="shopengine-wishlist-td shopengine-wishlist-td-price">
                <?php 
                    $regular_price = $product->get_regular_price();
                    $sale_price    = $product->get_sale_price();

                    if ( $sale_price ) {
                        echo '<ins>' . wc_price( $sale_price ) . '</ins>';
                        echo '<del>' . wc_price( $regular_price ) . '</del> ';
                    } else {
                        echo wc_price( $regular_price );
                    }
                ?>
                </td>

                <td class="shopengine-wishlist-td shopengine-wishlist-td-quantity">
                    <?php
                    if ( $product->is_sold_individually() ) {
                        echo '1';
                    } else {
                        $max_purchase_quantity = (int) $product->get_max_purchase_quantity();
                        $max_quantity_options  = $max_purchase_quantity > 0 ? min( $max_purchase_quantity, 20 ) : 10;
                        $use_stepper           = 'stepper' === $qty_field_style;
                        if ( $use_stepper ) {
                            echo '<div class="shopengine-wishlist-qty-stepper" style="display:inline-flex;align-items:stretch;">';
                            echo '<button type="button" class="shopengine-wishlist-qty-btn shopengine-wishlist-qty-minus" aria-label="' . esc_attr__( 'Decrease quantity', 'shopengine-pro' ) . '">−</button>';
                        }
                        ?>
                        <input
                            type="number"
                            class="shopengine-wishlist-quantity"
                            data-product-id="<?php echo absint( $product_id ); ?>"
                            aria-label="<?php echo esc_attr__( 'Select quantity', 'shopengine-pro' ); ?>"
                            min="1"
                            max="<?php echo esc_attr( $max_quantity_options ); ?>"
                            step="1"
                            value="1"
                        />
                        <?php
                        if ( $use_stepper ) {
                            echo '<button type="button" class="shopengine-wishlist-qty-btn shopengine-wishlist-qty-plus" aria-label="' . esc_attr__( 'Increase quantity', 'shopengine-pro' ) . '">+</button>';
                            echo '</div>';
                        }
                        ?>
                        <?php
                    }
                    ?>
                </td>
                
                <?php if ( $show_stock_column ) : ?>
                <td class="shopengine-wishlist-td shopengine-wishlist-td-stock">
                    <span class="shopengine-wishlist-stock shopengine-wishlist-stock--<?php echo esc_attr( $stock_modifier ); ?>"><?php echo esc_html( $stock_display ); ?></span>
                </td>
                <?php endif; ?>
                <td class="shopengine-wishlist-td shopengine-wishlist-td-add-cart">
                    <?php
                    if ( $product->is_purchasable() && $product->is_in_stock() ) {
                        $add_to_cart_url = $product->add_to_cart_url();
                        if ( ! $product->is_sold_individually() ) {
                            $add_to_cart_url = add_query_arg( 'quantity', 1, $add_to_cart_url );
                        }
                        ?>
                        <a class="shopengine-wishlist-add-to-cart" data-product-id="<?php echo absint( $product_id ); ?>" data-base-url="<?php echo esc_url( $product->add_to_cart_url() ); ?>" href="<?php echo esc_url( $add_to_cart_url ); ?>">
                            <?php echo esc_html( $product->add_to_cart_text() ); ?>
                        </a>
                        <?php
                    }
                    ?>
                </td>

                <?php if ( 'start' !== $action_column_position ) : ?>
                    <td class="shopengine-wishlist-td shopengine-wishlist-td-action product-remove">
                        <span class="shopengine-remove-action remove-badge remove-from-wishlist" data-pid="<?php echo absint( $product_id ); ?>" role="button" tabindex="0">
                            <?php \Elementor\Icons_Manager::render_icon( $settings['shopengine_wishlist_button_remove_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </span>
                    </td>
                <?php endif; ?>
            </tr>
                <?php
            }
        endif;
        ?>

        </tbody>
    </table>
</div>
<script>
    (function() {
        var root = document.currentScript && document.currentScript.previousElementSibling;
        if (!root || !root.classList || !root.classList.contains('shopengine-account-wishlist')) {
            root = document.querySelector('.shopengine-account-wishlist');
        }
        if (!root) {
            return;
        }

        var quantitySelectors = root.querySelectorAll('.shopengine-wishlist-quantity');

        var updateAddToCartUrl = function(selector) {
            var productId = selector.getAttribute('data-product-id');
            var quantity = parseInt(selector.value, 10);
            var addToCartLink = root.querySelector('.shopengine-wishlist-add-to-cart[data-product-id="' + productId + '"]');

            if (!addToCartLink || !quantity || quantity < 1) {
                return;
            }

            var baseUrl = addToCartLink.getAttribute('data-base-url');

            if (!baseUrl) {
                return;
            }

            var separator = baseUrl.indexOf('?') === -1 ? '?' : '&';
            var updatedUrl = baseUrl + separator + 'quantity=' + encodeURIComponent(quantity);

            addToCartLink.setAttribute('href', updatedUrl);
        };

        quantitySelectors.forEach(function(selector) {
            selector.addEventListener('change', function() {
                updateAddToCartUrl(selector);
            });
            selector.addEventListener('input', function() {
                updateAddToCartUrl(selector);
            });
        });

        root.querySelectorAll('.shopengine-wishlist-qty-stepper').forEach(function(wrap) {
            var input = wrap.querySelector('.shopengine-wishlist-quantity');
            var minus = wrap.querySelector('.shopengine-wishlist-qty-minus');
            var plus = wrap.querySelector('.shopengine-wishlist-qty-plus');
            if (!input || !minus || !plus) {
                return;
            }
            var clamp = function() {
                var min = parseInt(input.getAttribute('min'), 10) || 1;
                var max = parseInt(input.getAttribute('max'), 10);
                var v = parseInt(input.value, 10);
                if (isNaN(v) || v < min) {
                    v = min;
                }
                if (!isNaN(max) && max > 0 && v > max) {
                    v = max;
                }
                input.value = String(v);
            };
            minus.addEventListener('click', function() {
                input.stepDown();
                clamp();
                updateAddToCartUrl(input);
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });
            plus.addEventListener('click', function() {
                input.stepUp();
                clamp();
                updateAddToCartUrl(input);
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });
        });
    })();
</script>

<?php
