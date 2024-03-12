<?php
/**
 * Theme mods configuration.
 *
 * @package HiveTheme\Configs
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	'colors'       => [
		'fields' => [
			'primary_color'           => [
				'default' => '#FF5C6C',
			],

			'secondary_color'         => [
				'default' => '#0d2f81',
			],

			'primary_gradient_color'  => [
				'label'   => esc_html__( 'Primary Gradient Color', 'jobhive' ),
				'type'    => 'color',
				'default' => '#FCB80A',
			],

			'header_background_color' => [
				'label'   => esc_html__( 'Header Background Color', 'jobhive' ),
				'type'    => 'color',
				'default' => '#012132',
			],
		],
	],

	'fonts'        => [
		'fields' => [
			'heading_font'        => [
				'default' => 'DM Sans',
			],

			'heading_font_weight' => [
				'default' => '700',
			],

			'body_font'           => [
				'default' => 'DM Sans',
			],

			'body_font_weight'    => [
				'default' => '400,500',
			],
		],
	],

	'header_image' => [
		'fields' => [
			'header_image_parallax' => [
				'label'   => esc_html__( 'Enable parallax effect', 'jobhive' ),
				'type'    => 'checkbox',
				'default' => true,
			],
		],
	],
];
