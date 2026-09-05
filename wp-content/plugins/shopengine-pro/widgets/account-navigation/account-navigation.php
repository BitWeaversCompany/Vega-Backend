<?php

namespace Elementor;

use ShopEngine\Widgets\Products;

defined('ABSPATH') || exit;


class ShopEngine_Account_Navigation extends \ShopEngine\Base\Widget
{

	public function config() {
		return new ShopEngine_Account_Navigation_Config();
	}


	protected function register_controls() {


		/*
			----------------------------
			list Styles
			----------------------------
		*/

		$this->start_controls_section(
			'shopengine_account_navigation_list', [
				                                    'label' => esc_html__('Navigation List', 'shopengine-pro'),
				                                    'tab'   => Controls_Manager::TAB_STYLE,
			                                    ]
		);

		$this->add_control(
			'shopengine_account_navigation_list_padding',
			[
				'label'      => esc_html__('List Item Padding', 'shopengine-pro'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'default'    => [
					'top'      => '15',
					'right'    => '20',
					'bottom'   => '15',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-account-navigation ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'.rtl {{WRAPPER}} .shopengine-account-navigation ul li' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}} !important;',
				],
        ],
    	);

		$this->add_control(
			'shopengine_account_navigation_list_margin',
			[
				'label'      => esc_html__('List Item Margin', 'shopengine-pro'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-account-navigation ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'shopengine_account_navigation_list_border',
				'label'    => esc_html__('List Item Border', 'shopengine-pro'),
				'selector' => '{{WRAPPER}} .shopengine-account-navigation ul li',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '0',
							'bottom' => '1',
							'left' => '0',
							'unit' => 'px',
							'isLinked' => false,
						],
					],
					'color' => [
						'default' => '#e0e0e0',
					],
				],
			]
		);

		$this->add_control(
			'shopengine_account_navigation_list_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'shopengine-pro'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-account-navigation ul li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'shopengine_account_navigation_list_bg_color',
			[
				'label'     => esc_html__('List Item Background', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => true,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-navigation ul li' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'shopengine_account_navigation_list_hover_bg_color',
			[
				'label'     => esc_html__('List Item Hover Background', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => true,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-navigation ul li:hover' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'shopengine_account_navigation_show_circle_indicator',
			[
				'label'        => esc_html__('Show Circle Indicator', 'shopengine-pro'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'shopengine-pro'),
				'label_off'    => esc_html__('Hide', 'shopengine-pro'),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors'    => [
					'{{WRAPPER}} .shopengine-account-navigation ul li a::before' => 'display: {{VALUE}} !important;',
				],
				'selectors_dictionary' => [
					'yes' => 'block',
					''    => 'none',
				],
			]
		);

		$this->add_control(
			'shopengine_account_navigation_list_color',
			[
				'label'     => esc_html__('List Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#B1ADAD',
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-navigation ul li a'         => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .shopengine-account-navigation ul li a::before' => 'background-color: {{VALUE}};',
				],
			]
		);


		$this->add_control(
			'shopengine_account_navigation_list_hover_color',
			[
				'label'     => esc_html__('Hover and Active Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#3A3A3A',
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-navigation ul li a:hover'            => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .shopengine-account-navigation ul li a:hover:before'     => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .shopengine-account-navigation ul li.is-active a'        => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .shopengine-account-navigation ul li.is-active a:before' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'shopengine_account_navigation_list_active_bg',
			[
				'label'     => esc_html__('Active Background', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#F2F2F2',
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-navigation ul li.is-active' => 'background: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'shopengine_account_navigation_list_Typography',
				'label'    => esc_html__('Typography', 'shopengine-pro'),
				'selector' => '{{WRAPPER}} .shopengine-account-navigation ul li',
				'exclude'  => ['letter_spacing', 'font_style', 'text_decoration'],

				'fields_options' => [
					'typography'  => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '600',
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
						'default'    => [
							'size' => '55',
							'unit' => 'px'
						],
						'selectors' => [
							'{{WRAPPER}} .shopengine-account-navigation ul li' => 'line-height: {{SIZE}}{{UNIT}} !important;',
						],
						'size_units' => ['px']
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'           => 'shopengine_account_navigation_box_shadow',
				'label'          => esc_html__('List Box Shadow', 'shopengine-pro'),
				'selector'       => '{{WRAPPER}} .shopengine-account-navigation ul li',
				'exclude'        => ['box_shadow_position'],
				'fields_options' => [
					'box_shadow' => [
						'default' => [
							'horizontal' => 0,
							'vertical'   => 10,
							'blur'       => 20,
							'spread'     => 0,
							'color'      => 'rgba(0,0,0,0.08)',
						],
					],

				],

			]
		);


		$this->end_controls_section(); // end ./ list style

		/*
			----------------------------
			Container Styles
			----------------------------
		*/

		$this->start_controls_section(
			'shopengine_account_navigation_section', [
				                                       'label' => esc_html__('Navigation Container', 'shopengine-pro'),
				                                       'tab'   => Controls_Manager::TAB_STYLE,
			                                       ]
		);

		$this->add_control(
			'shopengine_account_navigation_container_border_color',
			[
				'label'     => esc_html__('Border Color', 'shopengine-pro'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#F2F2F2',
				'selectors' => [
					'{{WRAPPER}} .shopengine-account-navigation ul' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'shopengine_account_navigation_container_padding',
			[
				'label'      => esc_html__('Container Padding', 'shopengine-pro'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'default'    => [
					'top'      => '0',
					'right'    => '40',
					'bottom'   => '0',
					'left'     => '40',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .shopengine-account-navigation ul'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} ;',
					'.rtl {{WRAPPER}} .shopengine-account-navigation ul' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // end ./ Container Styles
	}


	protected function screen() {


		if(!is_user_logged_in()) {
			?>
            <div class="shopengine shopengine-editor-alert shopengine-editor-alert-warning">
				<?php echo esc_html__('You need first to be logged in', 'shopengine-pro') ?>
            </div>
			<?php
			return;
		}

		$tpl = Products::instance()->get_widget_template($this->get_name(), 'default', \ShopEngine_Pro::widget_dir());
		include $tpl;
	}
}
