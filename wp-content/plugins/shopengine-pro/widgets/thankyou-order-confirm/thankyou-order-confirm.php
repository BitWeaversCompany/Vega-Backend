<?php

namespace Elementor;

use ShopEngine\Core\Template_Cpt;
use ShopEngine\Widgets\Products;

defined('ABSPATH') || exit;


class ShopEngine_Thankyou_Order_Confirm extends \ShopEngine\Base\Widget
{

	public function config() {
		return new ShopEngine_Thankyou_Order_Confirm_Config();
	}

	protected function register_controls() {

		// Content Tab - Layout & Items
		$this->start_controls_section(
			'shopengine_thankyou_order_confirm_content_section',
			[
				'label' => esc_html__('Content', 'shopengine-pro'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_layout',
			[
				'label'   => esc_html__('Layout', 'shopengine-pro'),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'table' => esc_html__('Table', 'shopengine-pro'),
					'grid'  => esc_html__('Grid', 'shopengine-pro'),
				],
				'default' => 'table',
				'description' => esc_html__('Choose how to display order confirmation.', 'shopengine-pro'),
			]
		);

		$this->add_responsive_control(
			'shopengine_thankyou_order_confirm_grid_columns',
			[
				'label'       => esc_html__('Grid Columns', 'shopengine-pro'),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => ['px'],
				'range'       => [
					'px' => [
						'min'  => 1,
						'max'  => 5,
						'step' => 1,
					],
				],
				'devices'     => ['desktop', 'tablet', 'mobile'],
				'default'     => [
					'size' => 3,
					'unit' => 'px',
				],
				'render_type' => 'ui',
				'condition'   => ['shopengine_thankyou_order_confirm_layout' => 'grid'],
				'description' => esc_html__('Number of columns to show in grid layout (1–5).', 'shopengine-pro'),
				'selectors'   => [
					'{{WRAPPER}} .shopengine-order-confirm-grid' => 'display: grid; width: 100%; grid-template-columns: repeat({{SIZE}}, minmax(0, 1fr));',
				],
			]
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_items_heading',
			[
				'label'     => esc_html__('Display Items', 'shopengine-pro'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_display_items',
			[
				'label'       => esc_html__('Select Items to Display', 'shopengine-pro'),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => [
					'order_number'    => esc_html__('Order Number', 'shopengine-pro'),
					'order_date'      => esc_html__('Order Date', 'shopengine-pro'),
					'order_status'    => esc_html__('Order Status', 'shopengine-pro'),
					'payment_method'  => esc_html__('Payment Method', 'shopengine-pro'),
					'order_total'     => esc_html__('Order Total', 'shopengine-pro'),
				],
				'default'     => ['order_number', 'order_date', 'order_status', 'payment_method', 'order_total'],
				'description' => esc_html__('Choose which items to display. All are selected by default.', 'shopengine-pro'),
			]
		);

		$this->end_controls_section();

		// Style Tab - Table Body
		$this->start_controls_section(
			'shopengine_thankyou_order_confirm_table_body_section',
			[
				'label'     => esc_html__('Table Body', 'shopengine-pro'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => ['shopengine_thankyou_order_confirm_layout' => 'table'],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'shopengine_thankyou_order_confirm_table_body_typography',
				'label'          => esc_html__('Typography', 'shopengine-pro'),
				'selector'       => '{{WRAPPER}} .shopengine-thankyou-order-confirm table :not(thead) tr :is(th,td, span, a)',
				'exclude'        => ['font_family', 'text_decoration', 'font_style'],
				'fields_options' => [
					'typography'     => [
						'default' => 'custom',
					],
					'font_size'      => [
						'label'      => esc_html__('Font Size (px)', 'shopengine-pro'),
						'size_units' => ['px'],
						'default'    => [
							'size' => '16',
							'unit' => 'px'
						]
					],
					'font_weight'    => [
						'default' => '500',
					],
					'text_transform' => [
						'default' => '',
					],
					'line_height'    => [
						'label'      => esc_html__('Line Height (px)', 'shopengine-pro'),
						'default'    => [
							'size' => '20',
							'unit' => 'px',
						],
						'size_units' => ['px'],
						'tablet_default' => [
							'unit' => 'px',
						],
						'mobile_default' => [
							'unit' => 'px',
						],
					],
					'letter_spacing' => [
						'default' => [
							'size' => '0',
						]
					],
				],
			)
		);

		$this->start_controls_tabs('shopengine_thankyou_order_confirm_table_body_tabs');

		$this->start_controls_tab('shopengine_thankyou_order_confirm_table_body_normal_tab',
			[
				'label' => esc_html__('Normal', 'shopengine-pro'),
			]
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_table_body_color',
			[
				'label'     => esc_html__('Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3A3A3A',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-thankyou-order-confirm table :not(thead) tr:nth-child(even) :is(th, td, span, .amount)'	=> 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_table_body_bg_color',
			[
				'label'     => esc_html__('Background', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-thankyou-order-confirm table :not(thead) tr:nth-child(even)' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab('shopengine_thankyou_order_confirm_table_body_striped_tab',
			[
				'label' => esc_html__('Striped', 'shopengine-pro'),
			]
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_table_body_striped_color',
			[
				'label'     => esc_html__('Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3A3A3A',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-thankyou-order-confirm table :not(thead) tr:nth-child(odd) :is(th, td, span, .amount)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_table_body_striped_bg_color',
			[
				'label'     => esc_html__('Background', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F9F9F9',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-thankyou-order-confirm table :not(thead) tr:nth-child(odd)' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'shopengine_thankyou_order_confirm_table_body_link_color',
			[
				'label'     => esc_html__('Link Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4169E1',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-thankyou-order-confirm table :not(thead) tr a' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_table_body_link_hover_color',
			[
				'label'     => esc_html__('Link Hover Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#E65093',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-thankyou-order-confirm table :not(thead) tr a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           	=> 'shopengine_thankyou_order_confirm_table_body_border',
				'label'          	=> esc_html__('Border', 'shopengine-pro'),
				'fields_options'	=> [
					'border' => [
						'default' => 'solid',
					],
					'width'  => [
						'label'	=> esc_html__('Width (px)', 'shopengine-pro'),
						'size_units' => ['px'],
						'default' => [
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
							'isLinked' => true,
						],
						'selectors'  => [
							'{{WRAPPER}} .shopengine-thankyou-order-confirm table :not(thead) tr' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'.rtl {{WRAPPER}} .shopengine-thankyou-order-confirm table :not(thead) tr' => 'border-width: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
						],
					],
					'color'  => [
						'default' => '#F2F2F2'
					]
				],
				'selector'     		=> '{{WRAPPER}} .shopengine-thankyou-order-confirm table :not(thead) tr',
				'separator'			=> 'before',
			]
		);

		$this->add_responsive_control(
			'shopengine_thankyou_order_confirm_table_body_padding',
			[
				'label'      => esc_html__('Padding (px)', 'shopengine-pro'),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'      => '15',
					'right'    => '20',
					'bottom'   => '15',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'size_units' => ['px'],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-thankyou-order-confirm table :not(thead) tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.rtl {{WRAPPER}} .shopengine-thankyou-order-confirm table :not(thead) tr td' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};'
				],
				'separator'	=> 'before',
			]
		);

		$this->end_controls_section();

		// Style Tab - Grid Layout
		$this->start_controls_section(
			'shopengine_thankyou_order_confirm_grid_section',
			[
				'label'     => esc_html__('Grid Layout', 'shopengine-pro'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => ['shopengine_thankyou_order_confirm_layout' => 'grid'],
			]
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_alignment',
			[
				'label'      => esc_html__('Alignment', 'shopengine-pro'),
				'type'       => Controls_Manager::CHOOSE,
				'options'    => [
					'left'  => [
						'title' => esc_html__('Left', 'shopengine-pro'),
						'icon'  => 'eicon-text-align-left',
					],
					'center'     => [
						'title' => esc_html__('Center', 'shopengine-pro'),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__('Right', 'shopengine-pro'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'    => 'left',
				'selectors'  => [
					'{{WRAPPER}} .shopengine-order-confirm-grid' => 'text-align: {{VALUE}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_grid_item_heading',
			[
				'label'     => esc_html__('Item', 'shopengine-pro'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_grid_item_bg_color',
			[
				'label'     => esc_html__('Background Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F9F9F9',
				'alpha'     => true,
				'selectors' => [
					'{{WRAPPER}} .shopengine-order-confirm-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'shopengine_thankyou_order_confirm_grid_item_padding',
			[
				'label'      => esc_html__('Padding (px)', 'shopengine-pro'),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'      => '20',
					'right'    => '20',
					'bottom'   => '20',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'size_units' => ['px'],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-order-confirm-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-width: 0;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           	=> 'shopengine_thankyou_order_confirm_grid_item_border',
				'label'          	=> esc_html__('Border', 'shopengine-pro'),
				'fields_options'	=> [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
						],
					],
					'color' => [
						'default' => '#E8E8E8',
					],
				],
				'selector'     		=> '{{WRAPPER}} .shopengine-order-confirm-item',
			]
		);

		$this->add_responsive_control(
			'shopengine_thankyou_order_confirm_grid_item_border_radius',
			[
				'label'      => esc_html__('Border Radius (px)', 'shopengine-pro'),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'      => '4',
					'right'    => '4',
					'bottom'   => '4',
					'left'     => '4',
					'unit'     => 'px',
					'isLinked' => true,
				],
				'size_units' => ['px'],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-order-confirm-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'shopengine_thankyou_order_confirm_grid_item_gap',
			[
				'label'      => esc_html__('Item Gap (px)', 'shopengine-pro'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => 20,
				],
				'description' => esc_html__('Space between grid items. This also creates proper border spacing.', 'shopengine-pro'),
				'selectors'  => [
					'{{WRAPPER}} .shopengine-order-confirm-grid' => 'display: grid; width: 100%; gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_grid_label_heading',
			[
				'label'     => esc_html__('Item Label', 'shopengine-pro'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_grid_label_color',
			[
				'label'     => esc_html__('Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#9C9C9C',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-order-confirm-item .item-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'shopengine_thankyou_order_confirm_grid_label_typography',
				'label'          => esc_html__('Typography', 'shopengine-pro'),
				'selector'       => '{{WRAPPER}} .shopengine-order-confirm-item .item-label',
				'exclude'        => ['font_family', 'text_decoration', 'font_style'],
				'fields_options' => [
					'typography'     => [
						'default' => 'custom',
					],
					'font_size'      => [
						'label'      => esc_html__('Font Size (px)', 'shopengine-pro'),
						'size_units' => ['px'],
						'default'    => [
							'size' => '12',
							'unit' => 'px'
						]
					],
					'font_weight'    => [
						'default' => '500',
					],
					'text_transform' => [
						'default' => 'uppercase',
					],
					'line_height'    => [
						'label'      => esc_html__('Line Height (px)', 'shopengine-pro'),
						'default'    => [
							'size' => '16',
							'unit' => 'px',
						],
						'size_units' => ['px'],
					],
				],
			)
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_grid_value_heading',
			[
				'label'     => esc_html__('Item Value', 'shopengine-pro'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_grid_value_color',
			[
				'label'     => esc_html__('Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3A3A3A',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-order-confirm-item .item-value' => 'color: {{VALUE}}; white-space: normal; overflow-wrap: anywhere; word-break: break-word;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'shopengine_thankyou_order_confirm_grid_value_typography',
				'label'          => esc_html__('Typography', 'shopengine-pro'),
				'selector'       => '{{WRAPPER}} .shopengine-order-confirm-item .item-value',
				'exclude'        => ['font_family', 'text_decoration', 'font_style'],
				'fields_options' => [
					'typography'     => [
						'default' => 'custom',
					],
					'font_size'      => [
						'label'      => esc_html__('Font Size (px)', 'shopengine-pro'),
						'size_units' => ['px'],
						'default'    => [
							'size' => '18',
							'unit' => 'px'
						]
					],
					'font_weight'    => [
						'default' => '600',
					],
					'line_height'    => [
						'label'      => esc_html__('Line Height (px)', 'shopengine-pro'),
						'default'    => [
							'size' => '22',
							'unit' => 'px',
						],
						'size_units' => ['px'],
					],
				],
			)
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_grid_value_link_color',
			[
				'label'     => esc_html__('Link Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4169E1',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-order-confirm-item .item-value a' => 'color: {{VALUE}}; white-space: normal; overflow-wrap: anywhere; word-break: break-word; max-width: 100%;',
				],
			]
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_grid_value_link_hover_color',
			[
				'label'     => esc_html__('Link Hover Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#E65093',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-order-confirm-item .item-value a:hover' => 'color: {{VALUE}};',
				],
			]
		);



		$this->end_controls_section();

		// Style Tab - Global Font
		$this->start_controls_section(
			'shopengine_thankyou_order_confirm_global_font_section',
			[
				'label' => esc_html__('Global Font', 'shopengine-pro'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'shopengine_thankyou_order_confirm_font_family',
			[
				'label'       => esc_html__('Font Family', 'shopengine-pro'),
				'description' => esc_html__('This font family is set for this specific widget.', 'shopengine-pro'),
				'type'        => Controls_Manager::FONT,
				'selectors'   => [
					'{{WRAPPER}} .shopengine-thankyou-order-confirm table :is(tr, th, td, span, .amount, a)' => 'font-family: {{VALUE}};',
					'{{WRAPPER}} .shopengine-order-confirm-item :is(span, a, .item-label, .item-value)' => 'font-family: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}


	protected function screen() {

		$post_type = get_post_type();

		$order_id = $this->get_the_order_id();

		if($post_type == Template_Cpt::TYPE && $order_id == 0) {
			// get a order to show in editor mode
			$orders = \ShopEngine\Widgets\Products::instance()->get_a_order_id();

			if(!empty($orders[0])) {
				$order_id = $orders[0]->get_id();
			} else {
				?>
                <div class="shopengine shopengine-editor-alert shopengine-editor-alert-warning">
					<?php echo esc_html__('No order found.', 'shopengine-pro'); ?>
                </div>
				<?php

				return;
			}
		}

		$tpl = Products::instance()->get_widget_template($this->get_name(), 'default', \ShopEngine_Pro::widget_dir());


		include $tpl;
	}


	private function get_the_order_id() {

		global $wp;

		//todo- should we return the last order id for editor???

		return isset($wp->query_vars['order-received']) ? $wp->query_vars['order-received'] : 0;
	}
}
