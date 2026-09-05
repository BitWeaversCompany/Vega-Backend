<?php

defined('ABSPATH') || exit;

use ShopEngine\Widgets\Products;
use ShopEngine_Pro\Modules\Product_Size_Charts\Product_Size_Charts;

$product            = Products::instance()->get_product(get_post_type());
$product_size_chart = \ShopEngine\Core\Register\Module_List::instance()->get_list()['product-size-charts'];

if ($product_size_chart['status'] === 'active'):

    $chart_status = get_post_meta($product->get_id(), Product_Size_Charts::OPTION_STATUS_KEY, true);
    $chart_uid    = get_post_meta($product->get_id(), Product_Size_Charts::OPTION_KEY, true);
    $charts       = $product_size_chart['settings']['charts']['value'];

    if ($chart_status === 'yes' && !empty($chart_uid)) {

        $key = array_search($chart_uid, array_column($charts, '_uid'));

        if (false !== $key) {
            $chart = $charts[$key]['attachment_id'];
            include_once Products::instance()->get_widget_template($this->get_name(), 'view', \ShopEngine_Pro::widget_dir());
        }
    } else {

        $categories = get_the_terms($product->get_id(), 'product_cat');
        $chart_id   = false;

        if (!empty($categories) && !is_wp_error($categories)) {
            $category_ids = [];

            foreach ($categories as $category) {
                $category_ids[] = (int) $category->term_id;

                $ancestors = get_ancestors($category->term_id, 'product_cat');
                if (!empty($ancestors)) {
                    foreach ($ancestors as $ancestor_id) {
                        $category_ids[] = (int) $ancestor_id;
                    }
                }
            }

            $category_ids = array_values(array_unique($category_ids));

            $charts_by_category = [];
            foreach ($charts as $index => $chart_item) {
                if (!empty($chart_item['category_id'])) {
                    $charts_by_category[(int) $chart_item['category_id']] = $index;
                }
            }

            foreach ($category_ids as $category_id) {
                if (isset($charts_by_category[$category_id])) {
                    $chart_id = $charts_by_category[$category_id];
                    break;
                }
            }
        }

        if (false !== $chart_id) {
            $chart = $charts[$chart_id]['attachment_id'];
            include_once Products::instance()->get_widget_template($this->get_name(), 'view', \ShopEngine_Pro::widget_dir());
        }
    }

endif;