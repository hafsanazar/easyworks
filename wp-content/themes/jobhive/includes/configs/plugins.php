<?php
/**
 * Plugins configuration.
 *
 * @package HiveTheme\Configs
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	[
		'name' => 'HivePress Favorites',
		'slug' => 'hivepress-favorites',
	],

	[
		'name' => 'HivePress Messages',
		'slug' => 'hivepress-messages',
	],

	[
		'name' => 'HivePress Paid Listings',
		'slug' => 'hivepress-paid-listings',
	],

	[
		'name'   => 'HivePress Tags',
		'slug'   => 'hivepress-tags',
		'source' => hivetheme()->get_path( 'parent' ) . '/vendor/hivepress/hivepress-tags.zip',
	],

	[
		'name'   => 'HivePress Testimonials',
		'slug'   => 'hivepress-testimonials',
		'source' => hivetheme()->get_path( 'parent' ) . '/vendor/hivepress/hivepress-testimonials.zip',
	],
];
