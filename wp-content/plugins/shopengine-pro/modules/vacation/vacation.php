<?php

namespace ShopEngine_Pro\Modules\Vacation;

defined('ABSPATH') || exit;

use ShopEngine\Core\Register\Module_List;
use ShopEngine_Pro\Traits\Singleton;

class Vacation
{
    use Singleton;

    public $settings;
    public $vacation_status = false;
    public $module_status = false;
    public $notice_days_title; // for vacation widget

    public function init()
    {
        $wordpress_time_zone = wp_timezone_string();

        if (is_int(strpos($wordpress_time_zone, '+')) || is_int(strpos($wordpress_time_zone, '-'))) {
            return;
        }

        $this->module_status = true; // for widget

        date_default_timezone_set($wordpress_time_zone);

        $settings         = Module_List::instance()->get_settings('vacation');
        $regular_off_days = $settings['regular_off_days']['value'];
        $now_time         = time();

        if ($regular_off_days) {

            $today = strtolower(date('D'));

            if ('no' === $settings['enable_regular_off_days_time']['value']) {

                if (in_array($today, $regular_off_days)) {
                    $this->vacation_status = true;
                }

            } else {

                if (!empty($settings['start_time']['value']) && !empty($settings['end_time']['value'])) {

                    if (in_array($today, $regular_off_days)) {

                        $start_time_stamp = strtotime($settings['start_time']['value']);
                        $end_time_stamp   = strtotime($settings['end_time']['value']);

                        if ($start_time_stamp < $now_time) {

                            if ($end_time_stamp > $now_time) {
                                $this->vacation_status = true;
                            }
                        }
                        $this->notice_days_title = date('d/m/Y H:i:s A', $start_time_stamp) . ' - ' . date('d/m/Y H:i:s A', $end_time_stamp) . ' (' . date_default_timezone_get() . ')';
                    }
                }
            }
        }

        if (!$this->vacation_status) {

            foreach ($settings['vacation_days']['value'] as $day) {

                if (!empty($day['start_and_end_date'][0]) && !empty($day['start_and_end_date'][1])) {

                    if (strtotime($day['start_and_end_date'][0] . ' 00:00:00') < $now_time && strtotime($day['start_and_end_date'][1] . ' 24:00:00') > $now_time) {

                        $this->vacation_status = true;
                        break;
                    }
                }
            }
        }

        if ($this->vacation_status) {

            $this->settings = $settings;

            add_filter('woocommerce_order_button_html', function() {
                return '';
            });

            add_action('woocommerce_loop_add_to_cart_link', function ($content, $product, $arg) {

                if ($product->is_type('variable')) {
                    
                    ?>
                    <script>
                        jQuery(document).ready(function($) {
                            // Target variable product add to cart buttons
                            window.vacationStatus = <?php echo json_encode($this->vacation_status); ?>;
                            $(".add_to_cart_button[data-product_id='<?php echo esc_js($product->get_id()); ?>']").parent().css("cursor", "not-allowed");
                            $(".shopengine_direct_checkout").attr("disabled", true).css({"cursor": "not-allowed"}).removeAttr('href');
                        });
                    </script>
                    <?php
                }
                    ?>
                    <script>
                        window.vacationStatus = <?php echo json_encode($this->vacation_status); ?>;
                        jQuery(".add_to_cart_button").parent().css("cursor","not-allowed").removeAttr('href');
                        jQuery(".product_type_simple").attr("disabled", true).css({"cursor": "not-allowed"}).removeAttr('href');
                        jQuery(".shopengine_direct_checkout").attr("disabled", true).css({"cursor": "not-allowed"}).removeAttr('href');
                    </script>

                    <?php
                return str_replace('<a ', '<a disabled ', $content);
            }, 10, 3);

            add_action('woocommerce_after_add_to_cart_button', function () {
                
                global $product;

                if ($product->is_type('variable')) {
                    ?>
                   <script>
                jQuery(document).ready(function($) {
                    // Disable the "Add to Cart" button for variable products
                    $("form.variations_form").each(function() {
                        var $form = $(this);

                        // Disable the "Add to Cart" button
                        $form.find("button.single_add_to_cart_button").attr("disabled", true).css({"cursor": "not-allowed"}).removeAttr('href').on("click", function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                        });

                        $form.find(".shopengine-quick-checkout-button").attr("href", "#") .css({"cursor": "not-allowed"}) .attr("aria-disabled", "true") .off("click") 
                        .on("click", function(e) {
                            e.preventDefault(); 
                            e.stopPropagation(); 
                        });
                    });

                });
                </script>
                    <?php
                }
            ?>
            <script>
                jQuery("button[name='add-to-cart']").attr("disabled", true).css("cursor","not-allowed");
                jQuery(".shopengine-quick-checkout-button").attr("disabled", true).css({"cursor": "not-allowed"}) .removeAttr('href').on("click", function(e) { e.preventDefault();           
                e.stopPropagation();          
            });
            </script>
            <?php
            });

            add_action('woocommerce_after_cart_totals', function () {
            ?>
            <script>
                jQuery(".checkout-button").removeAttr('href');
                jQuery(".checkout-button").addClass("checkout-disable");
            </script>
            <?php
            });

            add_action('woocommerce_cart_coupon', function () {
            ?>
            <script>
                jQuery("button[name='apply_coupon']").attr("disabled", true);
            </script>
            <?php
            });
        }
    }
}
