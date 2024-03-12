<?php
/**
 * Theme styles configuration.
 *
 * @package HiveTheme\Configs
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	[
		'selector'   => '
			.content-title::before,
			.widget--footer .widget__title::before,
			.post__categories a:hover,
			.hp-page__title::before,
			.hp-section__title::before,
			.hp-listing--view-page .hp-listing__title::before
		',

		'properties' => [
			[
				'name'      => 'background-color',
				'theme_mod' => 'primary_color',
			],
		],
	],

	[
		'selector'   => '
			.tagcloud a:hover,
			.wp-block-tag-cloud a:hover,
			.hp-listing__images-carousel .slick-current img
		',

		'properties' => [
			[
				'name'      => 'border-color',
				'theme_mod' => 'primary_color',
			],
		],
	],

	[
		'selector'   => '
			.hp-listing--view-block .hp-listing__details--primary a:hover,
			.hp-listing--view-page .hp-listing__details--primary a:hover,
			.hp-listing-categories.hp-grid .hp-grid__item:hover .hp-listing-category__icon i
		',

		'properties' => [
			[
				'name'      => 'color',
				'theme_mod' => 'primary_color',
			],
		],
	],

	[
		'selector'   => '
			.post--archive .post__header .post__date,
			.hp-testimonials--slider .slick-arrows
		',

		'properties' => [
			[
				'name'      => 'background-color',
				'theme_mod' => 'secondary_color',
			],
		],
	],

	[
		'selector'   => '
			.hp-listing-category--view-block .hp-listing-category__icon i
		',

		'properties' => [
			[
				'name'      => 'color',
				'theme_mod' => 'secondary_color',
			],
		],
	],

	[
		'selector'   => '
			.site-header
		',

		'properties' => [
			[
				'name'      => 'background-color',
				'theme_mod' => 'header_background_color',
			],
		],
	],
];
