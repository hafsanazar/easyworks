<?php
/**
 * Theme component.
 *
 * @package HiveTheme\Components
 */

namespace HiveTheme\Components;

use HiveTheme\Helpers as ht;
use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Theme component class.
 *
 * @class Theme
 */
final class Theme extends Component {

	/**
	 * Class constructor.
	 *
	 * @param array $args Component arguments.
	 */
	public function __construct( $args = [] ) {

		// Set hero background.
		add_action( 'wp_enqueue_scripts', [ $this, 'set_hero_background' ] );

		// Render hero content.
		add_filter( 'hivetheme/v1/areas/site_hero', [ $this, 'render_hero_content' ] );

		// Alter styles.
		add_filter( 'hivetheme/v1/styles', [ $this, 'alter_styles' ] );
		add_filter( 'hivepress/v1/styles', [ $this, 'alter_styles' ] );

		// Check HivePress status.
		if ( ! ht\is_plugin_active( 'hivepress' ) ) {
			return;
		}

		// Alter strings.
		add_filter( 'hivepress/v1/strings', [ $this, 'alter_strings' ] );

		// Alter post types.
		add_filter( 'hivepress/v1/post_types', [ $this, 'alter_post_types' ] );

		// Alter taxonomies.
		add_filter( 'hivepress/v1/taxonomies', [ $this, 'alter_taxonomies' ] );

		// Alter blocks.
		add_filter( 'hivepress/v1/blocks/testimonials/meta', [ $this, 'alter_slider_block_meta' ] );
		add_filter( 'hivepress/v1/blocks/testimonials', [ $this, 'alter_slider_block_args' ], 10, 2 );

		// Alter models.
		add_filter( 'hivepress/v1/models/listing_category', [ $this, 'alter_listing_category_fields' ] );

		if ( is_admin() ) {

			// Alter meta boxes.
			add_filter( 'hivepress/v1/meta_boxes/listing_category_settings', [ $this, 'alter_listing_category_settings' ] );
		} else {

			// Alter templates.
			add_filter( 'hivepress/v1/templates/listings_view_page', [ $this, 'alter_listings_view_page' ] );
			add_filter( 'hivepress/v1/templates/vendor_view_page', [ $this, 'alter_listings_view_page' ] );
			add_filter( 'hivepress/v1/templates/listings_favorite_page', [ $this, 'alter_listings_favorite_page' ] );

			add_filter( 'hivepress/v1/templates/listing_categories_view_page', [ $this, 'alter_listing_submit_category_page' ] );

			add_filter( 'hivepress/v1/templates/listing_view_block/blocks', [ $this, 'alter_listing_view_block' ], 10, 2 );
			add_filter( 'hivepress/v1/templates/listing_view_page', [ $this, 'alter_listing_view_page' ], 100 );

			add_filter( 'hivepress/v1/templates/vendors_view_page', [ $this, 'alter_vendors_view_page' ] );

			add_filter( 'hivepress/v1/templates/vendor_view_block', [ $this, 'alter_vendor_view_block' ] );
			add_filter( 'hivepress/v1/templates/vendor_view_page', [ $this, 'alter_vendor_view_page' ] );
		}

		parent::__construct( $args );
	}

	/**
	 * Sets hero background.
	 */
	public function set_hero_background() {
		$style = '';

		// Get image URL.
		$image_url = get_header_image();

		if ( is_singular( [ 'post', 'page' ] ) && has_post_thumbnail() ) {
			$image_url = get_the_post_thumbnail_url( null, 'ht_cover_large' );
		} elseif ( ht\is_plugin_active( 'hivepress' ) && is_tax( 'hp_listing_category' ) ) {
			$image_id = get_term_meta( get_queried_object_id(), 'hp_image', true );

			if ( $image_id ) {
				$image = wp_get_attachment_image_src( $image_id, 'ht_cover_large' );

				if ( $image ) {
					$image_url = ht\get_first_array_value( $image );
				}
			}
		}

		// Add background styles.
		if ( $image_url ) {
			$style .= '.site-header { background-image: url(' . esc_url( $image_url ) . ') }';
			$style .= '.site-header::before { opacity: 1 }';
		}

		if ( get_header_textcolor() && get_header_textcolor() !== 'blank' ) {
			$style .= '.site-header { color: #' . esc_attr( get_header_textcolor() ) . ' }';
		}

		// Add gradient styles.
		if ( get_theme_mod( 'primary_gradient_color' ) ) {
			$style .= '
				.content-title::before,
				.widget--footer .widget__title::before,
				.hp-page__title::before,
				.hp-section__title::before,
				.hp-listing--view-page .hp-listing__title::before,
				.button--primary,
				button[type="submit"],
				input[type=submit],
				.wp-block-button.is-style-primary .wp-block-button__link,
				.woocommerce #respond input#submit.alt,
				.woocommerce button[type=submit],
				.woocommerce input[type=submit],
				.woocommerce button[type=submit]:hover,
				.woocommerce input[type=submit]:hover,
				.woocommerce a.button.alt,
				.woocommerce button.button.alt,
				.woocommerce input.button.alt,
				.woocommerce #respond input#submit.alt:hover,
				.woocommerce a.button.alt:hover,
				.woocommerce button.button.alt:hover,
				.woocommerce input.button.alt:hover
				{ background-image: linear-gradient( to right, ' . esc_attr( get_theme_mod( 'primary_color' ) ) . ', ' . esc_attr( get_theme_mod( 'primary_gradient_color' ) ) . ') }
			';
		}

		// Add inline style.
		if ( $style ) {
			wp_add_inline_style( 'hivetheme-parent-frontend', $style );
		}
	}

	/**
	 * Renders hero content.
	 *
	 * @param string $output Hero content.
	 * @return string
	 */
	public function render_hero_content( $output ) {
		$classes = [];

		// Render header.
		if ( is_page() ) {

			// Get content.
			$content = '';

			$parts = get_extended( get_post_field( 'post_content' ) );

			if ( $parts['extended'] ) {
				$content = apply_filters( 'the_content', $parts['main'] );

				$classes[] = 'header-hero--large';
			} else {
				$classes[] = 'header-hero--title';
			}

			// Check title.
			$title = get_the_ID() !== absint( get_option( 'page_on_front' ) );

			if ( ht\is_plugin_active( 'hivepress' ) ) {
				$title = $title && ! hivepress()->request->get_context( 'post_query' );
			}

			// Render part.
			if ( $content ) {
				$output .= $content;
			} elseif ( $title ) {
				$output .= hivetheme()->template->render_part( 'templates/page/page-title' );
			}
		} elseif ( is_singular( 'post' ) ) {

			// Add classes.
			$classes = array_merge(
				$classes,
				[
					'post',
					'post--single',
					'header-hero--large',
					'header-hero--bottom',
				]
			);

			// Render part.
			$output .= hivetheme()->template->render_part( 'templates/post/single/post-header' );
		} elseif ( ht\is_plugin_active( 'hivepress' ) && is_tax( 'hp_listing_category' ) ) {

			// Add classes.
			$classes = array_merge(
				$classes,
				[
					'hp-listing-category',
					'hp-listing-category--view-page',
					'header-hero--large',
				]
			);

			// Render part.
			$output .= hivetheme()->template->render_part(
				'hivepress/listing-category/view/page/listing-category-header',
				[
					'listing_category' => \HivePress\Models\Listing_Category::query()->get_by_id( get_queried_object() ),
				]
			);
		}

		// Add wrapper.
		if ( $output ) {
			$output = hivetheme()->template->render_part(
				'templates/page/page-header',
				[
					'class'   => implode( ' ', $classes ),
					'content' => $output,
				]
			);
		}

		return $output;
	}

	/**
	 * Alters styles.
	 *
	 * @param array $styles Styles.
	 * @return array
	 */
	public function alter_styles( $styles ) {
		$styles['fontawesome']['src'] = hivetheme()->get_url( 'parent' ) . '/assets/css/fontawesome.min.css';

		unset( $styles['fontawesome_solid'] );

		return $styles;
	}

	/**
	 * Alters strings.
	 *
	 * @param array $strings Strings.
	 * @return array
	 */
	public function alter_strings( $strings ) {
		return array_merge(
			$strings,
			[
				'listing'                               => esc_html__( 'Job', 'jobhive' ),
				'listings'                              => esc_html__( 'Jobs', 'jobhive' ),
				/* translators: %s: Jobs number. */
				'n_listings'                            => _n_noop( '%s Job', '%s Jobs', 'jobhive' ),
				/* translators: %s: Company name. */
				'listings_by_vendor'                    => esc_html__( 'Jobs at %s', 'jobhive' ),
				'all_listings'                          => esc_html__( 'All Jobs', 'jobhive' ),
				'add_listing'                           => esc_html__( 'Post a Job', 'jobhive' ),
				'view_listings'                         => esc_html__( 'View Jobs', 'jobhive' ),
				'listing_categories'                    => esc_html__( 'Job Categories', 'jobhive' ),
				'listing_tags'                          => esc_html__( 'Job Tags', 'jobhive' ),
				'listing_packages'                      => esc_html__( 'Job Packages', 'jobhive' ),
				'listing_search_form'                   => esc_html__( 'Job Search Form', 'jobhive' ),
				'listing_attributes'                    => esc_html__( 'Job Attributes', 'jobhive' ),
				'related_listings'                      => esc_html__( 'Related Jobs', 'jobhive' ),
				'reply_to_listing'                      => esc_html__( 'Apply Now', 'jobhive' ),
				'listings_found'                        => esc_html__( 'Jobs Found', 'jobhive' ),

				'vendor'                                => esc_html__( 'Company', 'jobhive' ),
				'vendors'                               => esc_html__( 'Companies', 'jobhive' ),
				'view_vendor'                           => esc_html__( 'View Company', 'jobhive' ),
				'add_vendor'                            => esc_html__( 'Add Company', 'jobhive' ),
				'edit_vendor'                           => esc_html__( 'Edit Company', 'jobhive' ),
				'search_vendors'                        => esc_html__( 'Search Companies', 'jobhive' ),
				'no_vendors_found'                      => esc_html__( 'No companies found.', 'jobhive' ),
				'vendor_attributes'                     => esc_html__( 'Company Attributes', 'jobhive' ),
				'vendor_search_form'                    => esc_html__( 'Company Search Form', 'jobhive' ),
				'vendors_page'                          => esc_html__( 'Companies Page', 'jobhive' ),
				'regular_vendors_per_page'              => esc_html__( 'Companies per Page', 'jobhive' ),
				'choose_page_that_displays_all_vendors' => esc_html__( 'Choose a page that displays all companies.', 'jobhive' ),
				'display_vendors_on_frontend'           => esc_html__( 'Display companies on the front-end', 'jobhive' ),
				'display_only_verified_vendors'         => esc_html__( 'Display only verified companies', 'jobhive' ),
				'mark_vendor_as_verified'               => esc_html__( 'Mark this company as verified', 'jobhive' ),
				'only_vendors_can_make_offers'          => esc_html__( 'Only companies can make offers.', 'jobhive' ),
			]
		);
	}

	/**
	 * Alters post types.
	 *
	 * @param array $post_types Post types.
	 * @return array
	 */
	public function alter_post_types( $post_types ) {
		$post_types['listing']['rewrite']['slug'] = 'job';
		$post_types['vendor']['rewrite']['slug']  = 'company';

		$post_types['vendor']['menu_icon'] = 'dashicons-building';

		return $post_types;
	}

	/**
	 * Alters taxonomies.
	 *
	 * @param array $taxonomies Taxonomies.
	 * @return array
	 */
	public function alter_taxonomies( $taxonomies ) {
		$taxonomies['listing_category']['rewrite']['slug'] = 'job-category';
		$taxonomies['vendor_category']['rewrite']['slug']  = 'company-category';

		if ( isset( $taxonomies['listing_tags'] ) ) {
			$taxonomies['listing_tags']['rewrite']['slug'] = 'job-tag';
		}

		if ( isset( $taxonomies['listing_region'] ) ) {
			$taxonomies['listing_region']['rewrite']['slug'] = 'job-region';
		}

		return $taxonomies;
	}

	/**
	 * Alters slider block meta.
	 *
	 * @param array $meta Block meta.
	 * @return array
	 */
	public function alter_slider_block_meta( $meta ) {
		$meta['settings']['slider'] = [
			'label'  => esc_html__( 'Display in a slider', 'jobhive' ),
			'type'   => 'checkbox',
			'_order' => 100,
		];

		return $meta;
	}

	/**
	 * Alters slider block arguments.
	 *
	 * @param array  $args Block arguments.
	 * @param object $block Block object.
	 * @return array
	 */
	public function alter_slider_block_args( $args, $block ) {
		if ( hp\get_array_value( $args, 'slider' ) ) {
			$args['attributes'] = hp\merge_arrays(
				hp\get_array_value( $args, 'attributes', [] ),
				[
					'data-component' => 'slider',
					'data-type'      => 'carousel',
					'class'          => [ 'hp-testimonials--slider', 'alignfull' ],
				]
			);
		}

		return $args;
	}

	/**
	 * Alters listing category fields.
	 *
	 * @param array $model Model.
	 * @return array
	 */
	public function alter_listing_category_fields( $model ) {
		$model['fields']['icon'] = [
			'type'      => 'select',
			'options'   => 'icons',
			'_external' => true,
		];

		return $model;
	}

	/**
	 * Alters listing category settings.
	 *
	 * @param array $meta_box Meta box.
	 * @return array
	 */
	public function alter_listing_category_settings( $meta_box ) {
		$meta_box['fields']['icon'] = [
			'label'   => esc_html__( 'Icon', 'jobhive' ),
			'type'    => 'select',
			'options' => 'icons',
			'_order'  => 5,
		];

		return $meta_box;
	}

	/**
	 * Alters listings view page.
	 *
	 * @param array $template Template arguments.
	 * @return array
	 */
	public function alter_listings_view_page( $template ) {
		return hp\merge_trees(
			$template,
			[
				'blocks' => [
					'page_sidebar' => [
						'_order' => 30,
					],

					'listings'     => [
						'columns' => 1,
					],
				],
			]
		);
	}

	/**
	 * Alters listings favorite page.
	 *
	 * @param array $template Template arguments.
	 * @return array
	 */
	public function alter_listings_favorite_page( $template ) {
		return hp\merge_trees(
			$template,
			[
				'blocks' => [
					'listings' => [
						'columns' => 1,
					],
				],
			]
		);
	}

	/**
	 * Alters listing submit category page.
	 *
	 * @param array $template Template arguments.
	 * @return array
	 */
	public function alter_listing_submit_category_page( $template ) {
		return hp\merge_trees(
			$template,
			[
				'blocks' => [
					'listing_categories' => [
						'columns' => 4,
					],
				],
			]
		);
	}

	/**
	 * Alters listing view block.
	 *
	 * @param array  $blocks Template blocks.
	 * @param object $template Template object.
	 * @return array
	 */
	public function alter_listing_view_block( $blocks, $template ) {

		// Set blocks.
		$new_blocks = [
			'listing_location'             => [
				'type' => 'content',
			],

			'listing_featured_badge'       => [
				'type' => 'content',
			],

			'listing_image'                => [
				'path' => 'listing/view/block/vendor-image',
			],

			'listing_title'                => [
				'tag'    => 'h3',

				'blocks' => [
					'listing_featured_badge' => [
						'type'   => 'part',
						'path'   => 'listing/view/listing-featured-badge',
						'_order' => 30,
					],
				],
			],

			'listing_attributes_secondary' => [
				'type'       => 'container',

				'attributes' => [
					'class' => [ 'hp-listing__attributes', 'hp-listing__attributes--secondary' ],
				],

				'blocks'     => [
					'listing_location'                  => [
						'type'   => 'part',
						'path'   => 'listing/view/listing-location',
						'_order' => 10,
					],

					'listing_attributes_secondary_loop' => [
						'type'   => 'part',
						'path'   => 'listing/view/block/listing-attributes-secondary',
						'_order' => 20,
					],
				],
			],
		];

		if ( get_option( 'hp_vendor_enable_display' ) ) {

			// Get listing.
			$listing = $template->get_context( 'listing' );

			if ( $listing ) {

				// Get vendor.
				$vendor = $listing->get_vendor();

				if ( $vendor && $vendor->get_status() === 'publish' ) {

					// Set context.
					$template->set_context( 'vendor', $vendor );

					// Add blocks.
					$new_blocks['listing_details_primary'] = [
						'blocks' => [
							'listing_vendor' => [
								'type'   => 'part',
								'path'   => 'listing/view/block/listing-vendor',
								'_order' => 5,
							],
						],
					];
				}
			}
		}

		return hp\merge_trees(
			[ 'blocks' => $blocks ],
			[ 'blocks' => $new_blocks ]
		)['blocks'];
	}

	/**
	 * Alters listing view page.
	 *
	 * @param array $template Template arguments.
	 * @return array
	 */
	public function alter_listing_view_page( $template ) {
		return hp\merge_trees(
			$template,
			[
				'blocks' => [
					'listing_vendor'               => [
						'_order' => 5,
					],

					'related_listings'             => [
						'columns' => 1,
					],

					'listing_location'             => [
						'type' => 'content',
					],

					'listing_attributes_secondary' => [
						'type'       => 'container',
						'_order'     => 25,

						'attributes' => [
							'class' => [ 'hp-listing__attributes', 'hp-listing__attributes--secondary' ],
						],

						'blocks'     => [
							'listing_location' => [
								'type'   => 'part',
								'path'   => 'listing/view/listing-location',
								'_order' => 10,
							],

							'listing_attributes_secondary_loop' => [
								'type'   => 'part',
								'path'   => 'listing/view/page/listing-attributes-secondary',
								'_order' => 20,
							],
						],
					],
				],
			]
		);
	}

	/**
	 * Alters vendors view page.
	 *
	 * @param array $template Template arguments.
	 * @return array
	 */
	public function alter_vendors_view_page( $template ) {
		return hp\merge_trees(
			$template,
			[
				'blocks' => [
					'page_sidebar' => [
						'_order' => 30,
					],
				],
			]
		);
	}

	/**
	 * Alters vendor view block.
	 *
	 * @param array $template Template arguments.
	 * @return array
	 */
	public function alter_vendor_view_block( $template ) {
		return hp\merge_trees(
			$template,
			[
				'blocks' => [
					'vendor_name' => [
						'tag' => 'h3',
					],
				],
			]
		);
	}

	/**
	 * Alters vendor view page.
	 *
	 * @param array $template Template arguments.
	 * @return array
	 */
	public function alter_vendor_view_page( $template ) {
		return hp\merge_trees(
			$template,
			[
				'blocks' => [
					'vendor_name' => [
						'tag' => 'h2',
					],
				],
			]
		);
	}
}
