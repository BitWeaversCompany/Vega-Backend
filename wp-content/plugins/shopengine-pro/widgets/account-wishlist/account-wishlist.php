<?php

namespace Elementor;

use ShopEngine\Widgets\Products;

defined('ABSPATH') || exit;


class ShopEngine_Account_Wishlist extends \ShopEngine\Base\Widget
{

	public function config() {
		return new ShopEngine_Account_Wishlist_Config();
	}

	protected function register_controls() {

		$this->start_controls_section(
			'shopengine_wishlist_section_layout',
			[
				'label' => esc_html__( 'Layout', 'shopengine-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'shopengine_wishlist_show_stock_column',
			[
				'label'        => esc_html__( 'Stock Status Column', 'shopengine-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'shopengine-pro' ),
				'label_off'    => esc_html__( 'Hide', 'shopengine-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'shopengine_wishlist_product_layout',
			[
				'label'   => esc_html__( 'Product Cell Layout', 'shopengine-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'horizontal' => esc_html__( 'Image beside title', 'shopengine-pro' ),
					'vertical'   => esc_html__( 'Image above title', 'shopengine-pro' ),
				],
				'default' => 'horizontal',
			]
		);

		$this->add_control(
			'shopengine_wishlist_qty_field_style',
			[
				'label'   => esc_html__( 'Quantity Field', 'shopengine-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'native'  => esc_html__( 'Number input', 'shopengine-pro' ),
					'stepper' => esc_html__( 'Stepper (+ / −)', 'shopengine-pro' ),
				],
				'default' => 'stepper',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'shopengine_section_wishlist_table_wrapper',
			[
				'label' => esc_html__( 'Table Container', 'shopengine-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_table_width',
			[
				'label'      => esc_html__( 'Table Width', 'shopengine-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range'      => [
					'%'  => [ 'min' => 10, 'max' => 100 ],
					'px' => [ 'min' => 200, 'max' => 1400 ],
				],
				'default'    => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_table_border_collapse',
			[
				'label'        => esc_html__( 'Border Collapse', 'shopengine-pro' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'collapse' => esc_html__( 'Collapse', 'shopengine-pro' ),
					'separate' => esc_html__( 'Separate', 'shopengine-pro' ),
				],
				'default'      => 'collapse',
				'selectors'    => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist' => 'border-collapse: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'shopengine_wishlist_table_outer_border',
				'label'          => esc_html__( 'Outer Border', 'shopengine-pro' ),
				'selector'       => '{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width'  => [
						'default' => [
							'top'    => '0',
							'right'  => '0',
							'bottom' => '0',
							'left'   => '0',
							'unit'   => 'px',
						],
					],
					'color'  => [
						'default' => '#E8E8E8',
					],
				],
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_table_outer_radius',
			[
				'label'      => esc_html__( 'Outer Border Radius', 'shopengine-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_inner_vertical_borders',
			[
				'label'        => esc_html__( 'Inner Vertical Borders', 'shopengine-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'shopengine-pro' ),
				'label_off'    => esc_html__( 'Off', 'shopengine-pro' ),
				'return_value' => 'yes',
				'default'      => '',
				'selectors'    => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist :is(td, th)' => 'border-left-style: solid; border-right-style: solid; border-left-width: 1px; border-right-width: 1px;',
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_inner_vertical_border_color',
			[
				'label'     => esc_html__( 'Inner Vertical Border Color', 'shopengine-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#E8E8E8',
				'condition' => [
					'shopengine_wishlist_inner_vertical_borders' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist :is(td, th)' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		/*
			---------------------------------
			  Header Section
			------------------------------------
		 */

		$this->start_controls_section(
			'shopengine_section_wishlist_header',
			[
				'label' => esc_html__('Table Heading', 'shopengine-pro'),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'shopengine_wishlist_header_color',
			[
				'label'     => esc_html__('Text Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist thead th' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_header_background',
			[
				'label'     => esc_html__('Background', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#EDF2FE',
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist thead' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'shopengine_wishlist_header_text_typography',
				'label'    => esc_html__('Typography', 'shopengine-pro'),
				'selector' => '{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist thead th',
				'exclude'  => ['letter_spacing', 'text_decoration'],

				'fields_options' => [
					'typography'  => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_size'   => [
						'label'      => esc_html__('Font Size (px)', 'shopengine-pro'),
						'default'    => [
							'size' => '16',
							'unit' => 'px'
						],
						'size_units' => ['px']
					],
					'line_height' => [
						'label'      => esc_html__('Line-Height (px)', 'shopengine-pro'),
						'size_units' => ['px']
					],
				],
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_header_cell_padding',
			[
				'label'      => esc_html__( 'Header Cell Padding', 'shopengine-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'    => '10',
					'right'  => '16',
					'bottom' => '10',
					'left'   => '16',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'shopengine_wishlist_header_text_transform',
			[
				'label'     => esc_html__( 'Text Transform', 'shopengine-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''           => esc_html__( 'Default', 'shopengine-pro' ),
					'uppercase'  => esc_html__( 'Uppercase', 'shopengine-pro' ),
					'lowercase'  => esc_html__( 'Lowercase', 'shopengine-pro' ),
					'capitalize' => esc_html__( 'Capitalize', 'shopengine-pro' ),
					'none'       => esc_html__( 'None', 'shopengine-pro' ),
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist thead th' => 'text-transform: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_header_cells_column_align',
			[
				'label'     => esc_html__( 'Column Alignment (Header)', 'shopengine-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'shopengine-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'shopengine-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'shopengine-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'left',
				'toggle'    => false,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist thead th' => 'text-align: {{VALUE}};',
				],
			]
		);


		$this->end_controls_section(); // end ./ header section

		/*
			---------------------------------
			  Body Section Style
			------------------------------------
		 */

		$this->start_controls_section(
			'shopengine_section_wishlist_body',
			[
				'label' => esc_html__('Table Body', 'shopengine-pro'),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'shopengine_wishlist_body_bg_color',
			[
				'label'     => esc_html__('Body Background', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody tr' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_body_color',
			[
				'label'     => esc_html__('Text Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3A3A3A',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody td'       => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_body_link_color',
			[
				'label'     => esc_html__('Link Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody td a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_body_link_hover_color',
			[
				'label'     => esc_html__('Link Hover Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3A3A3A',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody td a:hover' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_body_vertical_align',
			[
				'label'     => esc_html__( 'Vertical Alignment', 'shopengine-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'top'    => [
						'title' => esc_html__( 'Top', 'shopengine-pro' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'shopengine-pro' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'shopengine-pro' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'   => 'middle',
				'toggle'    => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody td' => 'vertical-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_body_cells_column_align',
			[
				'label'     => esc_html__( 'Column Alignment (Cells)', 'shopengine-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'shopengine-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'shopengine-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'shopengine-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'left',
				'toggle'    => false,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody td' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'shopengine_wishlist_body_text_typography',
				'label'    => esc_html__('Text Typography', 'shopengine-pro'),
				'selector' => '{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody td',
				'exclude'  => ['letter_spacing', 'text_decoration'],

				'fields_options' => [
					'typography'  => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_size'   => [
						'label'      => esc_html__('Font Size (px)', 'shopengine-pro'),
						'default'    => [
							'size' => '16',
							'unit' => 'px'
						],
						'size_units' => ['px']
					],
					'line_height' => [
						'label'          => esc_html__('Line-Height (px)', 'shopengine-pro'),
						'size_units'     => ['px']
					],
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_body_row_heading',
			[
				'label'     => esc_html__( 'Row Border', 'shopengine-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'shopengine_wishlist_body_row_border',
				'label'    => esc_html__('Border', 'shopengine-pro'),
				'selector' => '{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody tr',
				'fields_options' => [
					'border_type' => [
						'default' => 'yes'
					],
					'border'      => [
						'default'    => 'solid',
						'devices'    => ['desktop'],
						'responsive' => true,
					],

					'width' => [
						'label'      => esc_html__('Border Width', 'shopengine-pro'),
						'default'    => [
							'top'    => '0',
							'right'  => '0',
							'bottom' => '1',
							'left'   => '0',
							'unit'   => 'px',
						],
						'devices'    => ['desktop'],
						'responsive' => true,
					],

					'color' => [
						'label'      => esc_html__('Border Row Color', 'shopengine-pro'),
						'alpha'      => false,
						'default'    => '#F2F2F2',
						'devices'    => ['desktop'],
						'responsive' => true,
					],
				],

			]
		);

		$this->add_control(
			'shopengine_wishlist_body_cell_padding_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_body_cell_padding',
			[
				'label'      => esc_html__('Cell Padding', 'shopengine-pro'),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'      => '10',
					'right'    => '16',
					'bottom'   => '10',
					'left'     => '16',
					'unit'     => 'px',
					'isLinked' => true,
				],
				'size_units' => ['px'],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.rtl {{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody tr td' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();  // end ./ body section style

		$this->start_controls_section(
			'shopengine_section_wishlist_product_cell',
			[
				'label' => esc_html__( 'Product Column', 'shopengine-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_product_image_width',
			[
				'label'      => esc_html__( 'Image Width', 'shopengine-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 40,
						'max'  => 300,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => 34,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-wishlist-product-image img' => 'width: {{SIZE}}{{UNIT}}; object-fit: cover;',
				],
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_product_image_height',
			[
				'label'      => esc_html__( 'Image Height', 'shopengine-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 40,
						'max'  => 300,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => 58,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-wishlist-product-image img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
				],
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_product_image_radius',
			[
				'label'      => esc_html__( 'Image Border Radius', 'shopengine-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-wishlist-product-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_product_inner_gap',
			[
				'label'      => esc_html__( 'Image & Title Spacing', 'shopengine-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 60,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => 12,
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-wishlist-product-inner' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'shopengine_wishlist_product_image_wrap_bg',
				'label'    => esc_html__( 'Image Area Background', 'shopengine-pro' ),
				'types'    => [ 'classic', 'gradient' ],
				'fields_options' => [
                    'background' => [
                        'default' => 'classic', // Ensures the 'Classic' tab is active by default
                    ],
                    'color' => [
                        'default' => '#F6F7F9', // Sets your default color
                    ],
                ],
				'selector' => '{{WRAPPER}} .shopengine-wishlist-product-image',
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_product_image_wrap_padding',
			[
				'label'      => esc_html__( 'Image Area Padding', 'shopengine-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'    => '10',
					'right'  => '10',
					'bottom' => '10',
					'left'   => '10',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-wishlist-product-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; display: inline-block;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'shopengine_wishlist_product_image_wrap_border',
				'label'    => esc_html__( 'Image Area Border', 'shopengine-pro' ),
				'selector' => '{{WRAPPER}} .shopengine-wishlist-product-image',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'shopengine_wishlist_product_title_typography',
				'label'    => esc_html__( 'Product Title Typography', 'shopengine-pro' ),
				'selector' => '{{WRAPPER}} .shopengine-wishlist-td-product .wishlist-product-name a',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'shopengine_section_wishlist_price_cell',
			[
				'label' => esc_html__( 'Price Column', 'shopengine-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'shopengine_wishlist_price_current_heading',
			[
				'label'     => esc_html__( 'Current / Sale Price', 'shopengine-pro' ),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'shopengine_wishlist_price_current_color',
			[
				'label'     => esc_html__( 'Color', 'shopengine-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .shopengine-wishlist-td-price ins' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .shopengine-wishlist-td-price > .woocommerce-Price-amount' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'shopengine_wishlist_price_current_typography',
				'label'    => esc_html__( 'Typography', 'shopengine-pro' ),
				'selector' => '{{WRAPPER}} .shopengine-wishlist-td-price ins, {{WRAPPER}} .shopengine-wishlist-td-price > .woocommerce-Price-amount',
			]
		);

		$this->add_control(
			'shopengine_wishlist_price_regular_heading',
			[
				'label'     => esc_html__( 'Regular / Old Price', 'shopengine-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'shopengine_wishlist_price_regular_color',
			[
				'label'     => esc_html__( 'Color', 'shopengine-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#828282',
				'selectors' => [
					'{{WRAPPER}} .shopengine-wishlist-td-price del' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'shopengine_wishlist_price_regular_typography',
				'label'    => esc_html__( 'Typography', 'shopengine-pro' ),
				'selector' => '{{WRAPPER}} .shopengine-wishlist-td-price del',
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_price_spacing',
			[
				'label'      => esc_html__( 'Spacing Between Prices', 'shopengine-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => 5,
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-wishlist-td-price ins' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'shopengine_section_wishlist_stock_cell',
			[
				'label'     => esc_html__( 'Stock Status', 'shopengine-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'shopengine_wishlist_show_stock_column' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'shopengine_wishlist_stock_typography',
				'label'    => esc_html__( 'Typography', 'shopengine-pro' ),
				'selector' => '{{WRAPPER}} .shopengine-wishlist-stock',
			]
		);

		$this->add_control(
			'shopengine_wishlist_stock_color_instock',
			[
				'label'     => esc_html__( 'In Stock', 'shopengine-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2E7D32',
				'selectors' => [
					'{{WRAPPER}} .shopengine-wishlist-stock--instock' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_stock_color_low',
			[
				'label'     => esc_html__( 'Low Stock', 'shopengine-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F57C00',
				'selectors' => [
					'{{WRAPPER}} .shopengine-wishlist-stock--low' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_stock_color_out',
			[
				'label'     => esc_html__( 'Out of Stock', 'shopengine-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#C62828',
				'selectors' => [
					'{{WRAPPER}} .shopengine-wishlist-stock--outofstock' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_stock_color_backorder',
			[
				'label'     => esc_html__( 'On Backorder', 'shopengine-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1565C0',
				'selectors' => [
					'{{WRAPPER}} .shopengine-wishlist-stock--onbackorder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'shopengine_section_wishlist_quantity',
			[
				'label' => esc_html__( 'Quantity Field', 'shopengine-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_qty_input_width',
			[
				'label'      => esc_html__( 'Input Width', 'shopengine-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 200,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => 60,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-wishlist-quantity' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%; box-sizing: border-box;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'shopengine_wishlist_qty_input_typography',
				'label'    => esc_html__( 'Input Typography', 'shopengine-pro' ),
				'selector' => '{{WRAPPER}} .shopengine-wishlist-quantity',
			]
		);

		$this->add_control(
			'shopengine_wishlist_qty_input_color',
			[
				'label'     => esc_html__( 'Text Color', 'shopengine-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .shopengine-wishlist-quantity' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_qty_input_bg',
			[
				'label'     => esc_html__( 'Background', 'shopengine-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .shopengine-wishlist-quantity' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'shopengine_wishlist_qty_input_border',
				'label'    => esc_html__( 'Border', 'shopengine-pro' ),
				'fields_options' => [
                    'border' => [
                        'default' => 'solid', // Sets border type to solid
                    ],
                    'color' => [
                        'default' => '#DBDBDB', // Sets border color
                    ],
                    'width' => [
                        'default' => [
                            'top'      => '0',
                            'right'    => '1',
                            'bottom'   => '0',
                            'left'     => '1',
                            'unit'     => 'px',
                            'isLinked' => false, // Unlinks the dimensions in the UI
                        ],
                    ],
                ],
				'selector' => '{{WRAPPER}} .shopengine-wishlist-quantity',
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_qty_input_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'shopengine-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-wishlist-quantity' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_qty_input_padding',
			[
				'label'      => esc_html__( 'Padding', 'shopengine-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'    => '5',
					'right'  => '8',
					'bottom' => '5',
					'left'   => '8',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-wishlist-quantity' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// $this->add_control(
		// 	'shopengine_wishlist_qty_hide_native_spinners',
		// 	[
		// 		'label'        => esc_html__( 'Hide Browser Spinners', 'shopengine-pro' ),
		// 		'type'         => Controls_Manager::SWITCHER,
		// 		'label_on'     => esc_html__( 'Yes', 'shopengine-pro' ),
		// 		'label_off'    => esc_html__( 'No', 'shopengine-pro' ),
		// 		'return_value' => 'yes',
		// 		'default'      => '',
		// 		'selectors'    => [
		// 			'{{WRAPPER}} .shopengine-wishlist-quantity' => '-moz-appearance: textfield;',
		// 			'{{WRAPPER}} .shopengine-wishlist-quantity::-webkit-outer-spin-button' => '-webkit-appearance: none; margin: 0;',
		// 			'{{WRAPPER}} .shopengine-wishlist-quantity::-webkit-inner-spin-button' => '-webkit-appearance: none; margin: 0;',
		// 		],
		// 	]
		// );

		$this->add_control(
			'shopengine_wishlist_qty_stepper_heading',
			[
				'label'     => esc_html__( 'Stepper (+ / −)', 'shopengine-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'shopengine_wishlist_qty_field_style' => 'stepper',
				],
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_qty_stepper_padding',
			[
				'label'      => esc_html__( 'Wrapper Padding', 'shopengine-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'condition'  => [
					'shopengine_wishlist_qty_field_style' => 'stepper',
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-wishlist-qty-stepper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; display: inline-flex; align-items: stretch;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'shopengine_wishlist_qty_stepper_bg',
				'label'     => esc_html__( 'Wrapper Background', 'shopengine-pro' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .shopengine-wishlist-qty-stepper',
				'condition' => [
					'shopengine_wishlist_qty_field_style' => 'stepper',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'shopengine_wishlist_qty_stepper_border',
				'label'     => esc_html__( 'Wrapper Border', 'shopengine-pro' ),
				'selector'  => '{{WRAPPER}} .shopengine-wishlist-qty-stepper',
				'fields_options' => [
					'border' => [
						'default' => 'solid', // Sets border type to solid
					],
					'color' => [
						'default' => '#D7D7D7', // Sets border color
					],
					'width' => [
						'default' => [
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
							'unit'     => 'px',
							'isLinked' => false, // Unlinks the dimensions in the UI
						],
					],
				],
				'condition' => [
					'shopengine_wishlist_qty_field_style' => 'stepper',
				],
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_qty_stepper_radius',
			[
				'label'      => esc_html__( 'Wrapper Border Radius', 'shopengine-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'condition'  => [
					'shopengine_wishlist_qty_field_style' => 'stepper',
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-wishlist-qty-stepper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->start_controls_tabs(
			'shopengine_wishlist_qty_btn_tabs',
			[
				'condition' => [
					'shopengine_wishlist_qty_field_style' => 'stepper',
				],
			]
		);

		$this->start_controls_tab(
			'shopengine_wishlist_qty_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'shopengine-pro' ),
			]
		);

		$this->add_control(
			'shopengine_wishlist_qty_btn_color',
			[
				'label'     => esc_html__( 'Color', 'shopengine-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} .shopengine-wishlist-qty-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_qty_btn_bg',
			[
				'label'     => esc_html__( 'Background', 'shopengine-pro' ),
				'default'   => '#ffffff',
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .shopengine-wishlist-qty-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'shopengine_wishlist_qty_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'shopengine-pro' ),
			]
		);

		$this->add_control(
			'shopengine_wishlist_qty_btn_color_h',
			[
				'label'     => esc_html__( 'Color', 'shopengine-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} .shopengine-wishlist-qty-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_qty_btn_bg_h',
			[
				'label'     => esc_html__( 'Background', 'shopengine-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffff',
				'selectors' => [
					'{{WRAPPER}} .shopengine-wishlist-qty-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'shopengine_wishlist_qty_btn_typography',
				'label'     => esc_html__( 'Stepper Button Typography', 'shopengine-pro' ),
				'selector'  => '{{WRAPPER}} .shopengine-wishlist-qty-btn',
				'condition' => [
					'shopengine_wishlist_qty_field_style' => 'stepper',
				],
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_qty_btn_min_width',
			[
				'label'      => esc_html__( 'Stepper Button Min Width', 'shopengine-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 24,
						'max'  => 80,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => 40,
				],
				'condition'  => [
					'shopengine_wishlist_qty_field_style' => 'stepper',
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-wishlist-qty-btn' => 'min-width: {{SIZE}}{{UNIT}}; flex: 0 0 auto; border: none; cursor: pointer; line-height: 1;',
				],
			]
		);

		$this->end_controls_section();

		/*
			---------------------------------
			  Action Buttons (Add to Cart / Remove)
			------------------------------------
		 */

		$this->start_controls_section(
			'shopengine_wishlist_button_action_section',
			[
				'label' => esc_html__('Action Buttons', 'shopengine-pro'),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		
		/**
		 * Add to cart button controls
		 */
		$this->add_control(
			'shopengine_wishlist_button_add_to_cart_heading',
			[
				'label'     => esc_html__('Add to Cart Button', 'shopengine-pro'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->start_controls_tabs('shopengine_wishlist_button_add_to_cart_style_tab');

		$this->start_controls_tab(
			'shopengine_wishlist_button_add_to_cart_tabnormal',
			[
				'label' => esc_html__('Normal', 'shopengine-pro'),
			]
		);

		$this->add_control(
			'shopengine_wishlist_button_add_to_cart_color_normal',
			[
				'label'     => esc_html__('Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody td .shopengine-wishlist-add-to-cart' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_button_add_to_cart_background_normal',
			[
				'label'     => esc_html__('Background', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody td .shopengine-wishlist-add-to-cart' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'shopengine_wishlist_button_add_to_cart_border',
				'label'    => esc_html__( 'Border', 'shopengine-pro' ),
				'selector' => '{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody td .shopengine-wishlist-add-to-cart',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width'  => [
						'default' => [
							'top'    => '0',
							'right'  => '0',
							'bottom' => '0',
							'left'   => '0',
							'unit'   => 'px',
						],
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'shopengine_wishlist_button_add_to_cart_tabhover',
			[
				'label' => esc_html__('Hover', 'shopengine-pro'),
			]
		);

		$this->add_control(
			'shopengine_wishlist_button_add_to_cart_color_hover',
			[
				'label'     => esc_html__('Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#3600FF',
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody td .shopengine-wishlist-add-to-cart:hover' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_button_add_to_cart_background_hover',
			[
				'label'     => esc_html__('Background', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody td .shopengine-wishlist-add-to-cart:hover' => 'background-color: {{VALUE}}'
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'shopengine_wishlist_button_add_to_cart_border_hover',
				'label'    => esc_html__( 'Border', 'shopengine-pro' ),
				'selector' => '{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody td .shopengine-wishlist-add-to-cart:hover',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width'  => [
						'default' => [
							'top'    => '0',
							'right'  => '0',
							'bottom' => '1',
							'left'   => '0',
							'unit'   => 'px',
						],
					],
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'shopengine_wishlist_button_add_to_cart_padding',
			[
				'label'      => esc_html__('Padding', 'shopengine-pro'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody td .shopengine-wishlist-add-to-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; display: inline-block; box-sizing: border-box;',
				],
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_button_add_to_cart_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'shopengine-pro'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody td .shopengine-wishlist-add-to-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'shopengine_wishlist_button_add_to_cart_typography',
				'label'    => esc_html__('Typography', 'shopengine-pro'),
				'selector' => '{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody td .shopengine-wishlist-add-to-cart',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'shopengine_wishlist_button_add_to_cart_box_shadow',
				'label'    => esc_html__('Box Shadow', 'shopengine-pro'),
				'selector' => '{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist tbody td .shopengine-wishlist-add-to-cart',
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_button_remove_align',
			[
				'label'     => esc_html__('Action Column Position', 'shopengine-pro'),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'start' => [
						'title' => esc_html__('Start', 'shopengine-pro'),
						'icon'  => 'eicon-text-align-left',
					],
					'end'   => [
						'title' => esc_html__('End', 'shopengine-pro'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'end',
				'toggle'    => false,
				'render_type' => 'template',
			]
		);
		
		/**
		 * Remove button controls
		 */
		$this->add_control(
			'shopengine_wishlist_button_remove_heading',
			[
				'label'     => esc_html__('Remove Button', 'shopengine-pro'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'shopengine_wishlist_button_remove_icon',
			[
				'label'     => esc_html__('Icon', 'shopengine-pro'),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value' => 'fas fa-times',
                    'library' => 'solid',
				],
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_button_remove_icon_size',
			[
				'label'      => esc_html__('Icon Size', 'shopengine-pro'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 8,
						'max'  => 72,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => 12,
					'unit' => 'px',
				],
				'selectors'  => [
                    '{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist td.product-remove .shopengine-remove-action i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist td.product-remove .shopengine-remove-action svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs('shopengine_wishlist_button_remove_style_tab');

		$this->start_controls_tab(
			'shopengine_wishlist_button_remove_tabnormal',
			[
				'label' => esc_html__('Normal', 'shopengine-pro'),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'shopengine_wishlist_button_remove_border',
				'label'    => esc_html__( 'Border', 'shopengine-pro' ),
				'fields_options' => [
                    'border' => [
                        'default' => 'solid', // Sets border type to solid
                    ],
                    'color' => [
                        'default' => '#7E8592', // Sets border color
                    ],
                    'width' => [
                        'default' => [
                            'top'      => '1',
                            'right'    => '1',
                            'bottom'   => '1',
                            'left'     => '1',
                            'unit'     => 'px',
                            'isLinked' => false, // Unlinks the dimensions in the UI
                        ],
                    ],
                ],
				'selector' => '{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist td.product-remove .shopengine-remove-action',
			]
		);

		$this->add_control(
			'shopengine_wishlist_button_remove_color_normal',
			[
				'label'     => esc_html__('Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7E8592',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist td.product-remove .shopengine-remove-action' => 'fill: {{VALUE}}; color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_button_remove_background_normal',
			[
				'label'     => esc_html__('Background', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist td.product-remove .shopengine-remove-action' => 'background: {{VALUE}}'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'shopengine_wishlist_button_remove_tabhover',
			[
				'label' => esc_html__('Hover', 'shopengine-pro'),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'shopengine_wishlist_button_remove_border_hover',
				'label'    => esc_html__( 'Border', 'shopengine-pro' ),
				'fields_options' => [
                    'border' => [
                        'default' => 'solid', // Sets border type to solid
                    ],
                    'color' => [
                        'default' => '#0055FF', // Sets border color
                    ],
                    'width' => [
                        'default' => [
                            'top'      => '1',
                            'right'    => '1',
                            'bottom'   => '1',
                            'left'     => '1',
                            'unit'     => 'px',
                            'isLinked' => false, // Unlinks the dimensions in the UI
                        ],
                    ],
                ],
				'selector' => '{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist td.product-remove .shopengine-remove-action:hover',
			]
		);

		$this->add_control(
			'shopengine_wishlist_button_remove_color_hover',
			[
				'label'     => esc_html__('Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist td.product-remove .shopengine-remove-action:hover' => 'fill:{{VALUE}}; color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'shopengine_wishlist_button_remove_background_hover',
			[
				'label'     => esc_html__('Background', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#002DFF',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist td.product-remove .shopengine-remove-action:hover' => 'background: {{VALUE}}'
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'shopengine_wishlist_button_remove_padding',
			[
				'label'      => esc_html__('Padding', 'shopengine-pro'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em'],
				'default'    => [
					'top'    => '5',
					'right'  => '5',
					'bottom' => '5',
					'left'   => '5',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist td.product-remove .shopengine-remove-action' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_button_remove_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'shopengine-pro'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'default'    => [
					'top'    => '50',
					'right'  => '50',
					'bottom' => '50',
					'left'   => '50',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist td.product-remove .shopengine-remove-action' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'shopengine_wishlist_button_remove_typography',
				'label'    => esc_html__('Typography', 'shopengine-pro'),
				'selector' => '{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist td.product-remove .shopengine-remove-action',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'shopengine_wishlist_button_remove_box_shadow',
				'label'    => esc_html__('Box Shadow', 'shopengine-pro'),
				'selector' => '{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist td.product-remove .shopengine-remove-action',
			]
		);

		$this->add_responsive_control(
			'shopengine_wishlist_button_remove_size',
			[
				'label'      => esc_html__('Button Size', 'shopengine-pro'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 20,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => 24,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-account-wishlist table.shopengine-wishlist td.product-remove .shopengine-remove-action' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; display: inline-flex; align-items: center; justify-content: center;',
				],
			]
		);

		$this->end_controls_section(); // end ./ action buttons
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function screen() {

		if(!is_user_logged_in()) {

			echo esc_html__('You need first to be logged in', 'shopengine-pro');

			return;
		}

		$list = get_user_meta(get_current_user_id(), \ShopEngine\Modules\Wishlist\Wishlist::UMK_WISHLIST, true);

		$tpl = Products::instance()->get_widget_template($this->get_name(), 'default', \ShopEngine_Pro::widget_dir());

		include $tpl;
	}
}
