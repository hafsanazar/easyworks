<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>
<time class="hp-vendor__registered-date" datetime="<?php echo esc_attr( $vendor->get_registered_date() ); ?>">
	<?php
	/* translators: %s: date. */
	printf( esc_html__( 'Joined %s ago', 'jobhive' ), human_time_diff( strtotime( $vendor->get_registered_date() ) ) );
	?>
</time>
