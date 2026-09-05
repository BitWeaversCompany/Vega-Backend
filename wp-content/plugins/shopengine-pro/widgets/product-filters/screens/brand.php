<?php
namespace Elementor;

defined('ABSPATH') || exit;

$uid = uniqid();

// check if the collapse enabled
$collapse	   = false;
$collapse_expand = '';

/**
 * 
 * Check weather the collapse enabled or not 
 * 
 */ 
if( $settings['shopengine_filter_view_mode'] === 'collapse' ) {
    $collapse	   = true;
}
/**
 * 
 * Check weather the collapse expand enable or not 
 * 
 */ 
//phpcs:ignore WordPress.Security.NonceVerification
if(  $settings['shopengine_filter_brand_expand_collapse'] === 'yes' || isset($_GET['shopengine_filter_brand'])) {
    $collapse_expand = 'open';
}

?>

<div class="shopengine-filter-single <?php echo esc_attr( $collapse ? 'shopengine-collapse' : '' ) ?>">
    
    <?php
        /**
         * 
         * show filter title
         * 
         */ 
        if ( isset( $settings['shopengine_filter_brand_title'] ) ) : 
        ?>
            <div class="shopengine-filter <?php echo esc_attr( $collapse_expand ) ?>">
                <h3 class="shopengine-product-filter-title">
                    <?php 
                        echo esc_html($settings['shopengine_filter_brand_title']);
                        if( $collapse ) echo '<i class="eicon-chevron-right shopengine-collapse-icon"></i>';
                    ?>
                </h3>
            </div>
        <?php 

        endif; // end of filter title 

        if( $collapse ) echo '<div class="shopengine-collapse-body '. esc_attr($collapse_expand) .'">';
        
            // Use brand-specific settings instead of category settings
            $height = isset($settings['shopengine_filter_brand_max_height']) && $settings['shopengine_filter_brand_max_height'] ? $settings['shopengine_filter_brand_max_height']['size'] : '';
            $is_scroll = isset($settings['shopengine_filter_brand_show_scrollbar']) && $settings['shopengine_filter_brand_show_scrollbar'] ? 'yes' : '';
            
    ?>

        <ul class="shopengine-brand-filter-list shopengine-filter-brand shopengine-filter-scroll-wrapper" data-height="<?php echo esc_attr($height); ?>" data-scroll="<?php echo esc_attr($is_scroll ?? ""); ?>">
            <?php
                /**
                 * 
                 * loop through list item
                 * 
                 */ 
                function display_brands($brands, $settings, $uid, $hierarchical, $hide_empty, $level = 0) {
                    foreach($brands as $brand) {
                        $has_children = !empty(get_term_children($brand->term_id, 'product_brand'));
                        $is_subbrand = $level > 0;
                        ?>
                        
                        <li class="<?php 
                            if ($has_children) { 
                                echo esc_attr('shopengine-filter-brand-has-child '); 
                                if($is_subbrand) {
                                    echo esc_attr('shopengine-filter-subbrand-has-child ');
                                }
                            } 
                        ?>">
        
                            <div class="filter-input-group">
                                <input
                                    class="shopengine-filter-brands shopengine-brand-name-<?php echo esc_attr($brand->slug); ?>"
                                    name="noNeed"
                                    type="checkbox"
                                    id="shopengine-filter-brand-<?php echo esc_attr($uid . '-' . $brand->term_id); ?>"
                                    value="<?php echo esc_attr($brand->slug); ?>" />
                                <label class="shopengine-filter-brand-label" for="shopengine-filter-brand-<?php echo esc_attr($uid . '-' . $brand->term_id); ?>">
                                    <?php 
                                    if(isset($settings['shopengine_filter_brand_styles'])) {
                                        $default = 'shopengine-checkbox-icon';
                                        $style2 = 'shopengine-style-icon';
                                        $class = $settings['shopengine_filter_brand_styles'] === 'style_2' ? $style2 : $default;
                                    } else {
                                        $class = 'shopengine-checkbox-icon';
                                    }
                                    ?>
                                    <span class="<?php echo esc_attr($class); ?>">
                                        <span>
                                            <?php 
                                            // Use brand-specific icons instead of shared ones
                                            if(isset($settings['shopengine_filter_brand_styles']) && $settings['shopengine_filter_brand_styles'] === 'style_2') {
                                                // Use brand circle icon
                                                if(isset($settings['shopengine_brand_circle_icon'])) {
                                                    Icons_Manager::render_icon($settings['shopengine_brand_circle_icon'], ['aria-hidden' => 'true']); 
                                                } else {
                                                    // Fallback to default circle icon
                                                    Icons_Manager::render_icon(['value' => 'fas fa-circle', 'library' => 'solid'], ['aria-hidden' => 'true']);
                                                }
                                            } else {
                                                // Use brand checkbox icon
                                                if(isset($settings['shopengine_brand_check_icon'])) {
                                                    Icons_Manager::render_icon($settings['shopengine_brand_check_icon'], ['aria-hidden' => 'true']); 
                                                } else {
                                                    // Fallback to default check icon
                                                    Icons_Manager::render_icon(['value' => 'fas fa-check', 'library' => 'fa-solid'], ['aria-hidden' => 'true']);
                                                }
                                            }
                                            ?>
                                        </span>
                                    </span>
                                    
									
									<?php 
									// Display brand thumbnail if available and enabled
									if (isset($settings['shopengine_filter_brand_show_image']) && $settings['shopengine_filter_brand_show_image'] === 'yes') {
										$thumbnail_id = get_term_meta($brand->term_id, 'thumbnail_id', true);
										if ($thumbnail_id) {
											$image = wp_get_attachment_image($thumbnail_id, 'thumbnail', false, array(
												'class' => 'brand-thumbnail',
												'style' => 'width: 24px; height: 24px; margin-right: 8px; vertical-align: middle;'
											));
											echo $image;
										}
									}
									?>
									<span><?php echo esc_html($brand->name, 'shopengine'); ?></span>
                                    <?php 
                                    if(isset($settings['shopengine_filter_brand_styles']) && $settings['shopengine_filter_brand_styles'] === 'style_2' && isset($settings['shopengine_filter_brand_show_count']) && $settings['shopengine_filter_brand_show_count'] === 'yes'):
                                    $args = array(
                                        'tax_query' => array(
                                            array(
                                                'taxonomy' => 'product_brand',
                                                'field'    => 'term_id',
                                                'terms'    => $brand->term_id,
                                            ),
                                        ),
                                        'limit' => -1,
                                    );
                                    $products = wc_get_products($args);
                                    ?>
                                    <span class="shopengine-filter-brand-count"><?php echo esc_html(count($products)); ?></span>
                                    <?php endif; ?>
                                </label>
        
                                <?php if ($has_children && $hierarchical === 'yes') : ?>
                                    <div class="shopengine-filter-brand-toggle <?php if($is_subbrand) { echo esc_attr('shopengine-filter-subbrand-toggle'); } ?>"
                                        aria-expanded="false"
                                        data-target="#shopengine-filter-subbrand-<?php echo esc_attr($uid . '-' . $brand->term_id); ?>">
                                        <span></span>
                                    </div>
                                <?php endif; ?>
                            </div>
        
                            <?php if ($has_children && $hierarchical === 'yes') : ?>
                                <?php
                                $child_brands = get_terms('product_brand', [
                                    'orderby' => 'name',
                                    'order' => 'asc',
                                    'hide_empty' => $hide_empty,
                                    'parent' => $brand->term_id,
                                ]);
                                
                                if (!empty($child_brands)) : ?>
                                    <ul class="shopengine-filter-brand-subbrands" id="shopengine-filter-subbrand-<?php echo esc_attr($uid . '-' . $brand->term_id); ?>">
                                        <?php 
                                        // Recursively display child brands
                                        display_brands($child_brands, $settings, $uid, $hierarchical, $hide_empty, $level + 1);
                                        ?>
                                    </ul>
                                <?php endif; ?>
                            <?php endif; ?>
        
                        </li>
                        <?php
                    }
                }
                
                // Display the top-level brands
                display_brands($product_brands, $settings, $uid, $hierarchical, $hide_empty);
                ?>
        </ul>

        <?php if( $collapse ) echo '</div>'; // end of collapse body container ?>

    <form
        action="" method="get"
        class="shopengine-filter" id="shopengine_brand_form">
        <input type="hidden" id="shopengine_filter_brand" name="shopengine_filter_brand" />
    </form>

</div>
