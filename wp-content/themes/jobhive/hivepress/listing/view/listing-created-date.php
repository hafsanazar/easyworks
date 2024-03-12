<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>
<time class="hp-listing__created-date" datetime="<?php echo esc_attr( $listing->get_created_date() ); ?>">
	<?php
	/* translators: %s: time period. */
	printf( esc_html__( '%s ago', 'jobhive' ), human_time_diff( strtotime( $listing->get_created_date() ) ) );
	?>
</time>
