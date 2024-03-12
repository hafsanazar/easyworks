<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>
<div class="hp-listing__image">
	<a href="<?php echo esc_url( hivepress()->router->get_url( 'listing_view_page', [ 'listing_id' => $listing->get_id() ] ) ); ?>">
		<?php if ( isset( $vendor ) && $vendor->get_image__url( 'hp_square_small' ) ) : ?>
			<img src="<?php echo esc_url( $vendor->get_image__url( 'hp_square_small' ) ); ?>" alt="<?php echo esc_attr( $listing->get_title() ); ?>" loading="lazy">
		<?php else : ?>
			<img src="<?php echo esc_url( hivepress()->get_url() . '/assets/images/placeholders/image-square.svg' ); ?>" alt="<?php echo esc_attr( $listing->get_title() ); ?>" loading="lazy">
		<?php endif; ?>
	</a>
</div>
