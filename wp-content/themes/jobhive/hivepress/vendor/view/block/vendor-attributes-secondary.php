<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( $vendor->_get_fields( 'view_block_secondary' ) ) :
	?>
	<div class="hp-vendor__attributes hp-vendor__attributes--secondary">
		<?php
		foreach ( $vendor->_get_fields( 'view_block_secondary' ) as $field ) :
			if ( ! is_null( $field->get_value() ) ) :
				?>
				<div class="hp-vendor__attribute hp-vendor__attribute--<?php echo esc_attr( $field->get_slug() ); ?>">
					<?php echo $field->display(); ?>
				</div>
				<?php
			endif;
		endforeach;
		?>
	</div>
	<?php
endif;
