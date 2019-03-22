<?php
/**
 * Room settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$thepostid = empty( $thepostid ) ? $post->ID : $thepostid;

?>

<?php wp_nonce_field( 'hotelier_save_data', 'hotelier_meta_nonce' ); ?>

<div class="room-settings">

	<div class="room-settings__general htl-ui-settings-wrap">
		<h3 class="htl-ui-heading htl-ui-heading--section-header"><?php esc_html_e( 'General settings', 'wp-hotelier' ); ?></h3>

		<?php
		HTL_Meta_Boxes_Helper::switch_input(
			array(
				'id'            => '_room_type',
				'value'         => get_post_meta( $thepostid, '_room_type', true ),
				'label'         => esc_html__( 'Room type:', 'wp-hotelier' ),
				'options'       => array(
					'standard_room' => esc_html__( 'Standard room', 'wp-hotelier' ),
					'variable_room' => esc_html__( 'Variable room', 'wp-hotelier' )
				),
				'std'           => 'standard_room',
				'wrapper_class' => 'htl-ui-switch--room-type'
			)
		);

		HTL_Meta_Boxes_Helper::number_input(
			array(
				'id'    => '_max_guests',
				'value' => get_post_meta( $thepostid, '_max_guests', true ),
				'label' => esc_html__( 'Max number of guests:', 'wp-hotelier' ),
				'min'   => 1,
				'std'   => 1,
			)
		);

		HTL_Meta_Boxes_Helper::number_input(
			array(
				'id'    => '_max_children',
				'value' => get_post_meta( $thepostid, '_max_children', true ),
				'label' => esc_html__( 'Children:', 'wp-hotelier' ),
				'min'   => 0,
				'std'   => 0,
			)
		);

		HTL_Meta_Boxes_Helper::text_input(
			array(
				'id'          => '_bed_size',
				'value'       => get_post_meta( $thepostid, '_bed_size', true ),
				'label'       => esc_html__( 'Bed size(s):', 'wp-hotelier' ),
				'placeholder' => esc_html__( '1 king', 'wp-hotelier' ),
			)
		);

		HTL_Meta_Boxes_Helper::number_input(
			array(
				'id'    => '_room_size',
				'value' => get_post_meta( $thepostid, '_room_size', true ),
				'label' => sprintf( esc_html__( 'Room size (%s):', 'wp-hotelier' ), htl_get_option( 'room_size_unit', 'm²' ) ),
				'min'   => 1,
			)
		);

		HTL_Meta_Boxes_Helper::number_input(
			array(
				'id'          => '_stock_rooms',
				'value'       => get_post_meta( $thepostid, '_stock_rooms', true ),
				'label'       => esc_html__( 'Stock rooms?', 'wp-hotelier' ),
				'description' => esc_html__( 'This is the total number of rooms available in the structure.', 'wp-hotelier' ),
				'min'         => 0,
				'std'         => 1,
			)
		);

		/**
		 * A filter is provided to allow extensions to add their own room general settings
		 */
		do_action( 'hotelier_room_general_settings' );

		HTL_Meta_Boxes_Helper::switch_input(
			array(
				'id'           => '_show_extra_settings',
				'value'        => get_post_meta( $thepostid, '_show_extra_settings', true ),
				'label'        => esc_html__( 'Show additional settings:', 'wp-hotelier' ),
				'options'      => array(
					'yes' => esc_html__( 'Yes', 'wp-hotelier' ),
					'no'  => esc_html__( 'No', 'wp-hotelier' ),
				),
				'std'          => 'no',
				'show-if'      => 'yes',
				'show-element' => 'extra-settings',
			)
		);
		?>

		<div class="htl-ui-setting-conditional htl-ui-setting-conditional--extra-settings" data-type="extra-settings">
			<?php
			/**
			 * A filter is provided to allow extensions to add their own room additional settings
			 */
			do_action( 'hotelier_room_before_additional_settings' );

			HTL_Meta_Boxes_Helper::textarea_input(
				array(
					'id'          => '_room_additional_details',
					'value'       => get_post_meta( $thepostid, '_room_additional_details', true ),
					'label'       => esc_html__( 'Additional details:', 'wp-hotelier' ),
					'description' => esc_html__( 'These details are not prominent by default; however, some themes may show them.', 'wp-hotelier' )
				)
			);

			/**
			 * A filter is provided to allow extensions to add their own room additional settings
			 */
			do_action( 'hotelier_room_after_additional_settings' );
			?>
		</div>

	</div>

	<div class="room-settings__standard htl-ui-settings-wrap">
		<h3 class="htl-ui-heading htl-ui-heading--section-header"><?php esc_html_e( 'Standard room settings', 'wp-hotelier' ); ?></h3>

		<div class="htl-ui-setting-group htl-ui-setting-group--metabox htl-ui-setting-group--price-options">
			<?php
			$price_type = array(
				'global'         => esc_html__( 'Global', 'wp-hotelier' ),
				'per_day'        => esc_html__( 'Price per day', 'wp-hotelier' ),
				'seasonal_price' => esc_html__( 'Seasonal price', 'wp-hotelier' )
			);

			HTL_Meta_Boxes_Helper::switch_input(
				array(
					'id'                   => '_price_type',
					'value'                => get_post_meta( $thepostid, '_price_type', true ),
					'label'                => esc_html__( 'Price type:', 'wp-hotelier' ),
					'options'              => apply_filters( 'hotelier_room_price_type', $price_type ),
					'std'                  => 'global',
					'conditional'          => true,
					'conditional-selector' => 'price-type',
				)
			);
			?>

			<div class="htl-ui-setting-conditional htl-ui-setting-conditional--price-type" data-type="global">
				<?php
				HTL_Meta_Boxes_Helper::price_input(
					array(
						'id'          => '_regular_price',
						'value'       => get_post_meta( $thepostid, '_regular_price', true ),
						'label'       => esc_html__( 'Regular price:', 'wp-hotelier' ),
						'description' => 'Same price for all days of the week.',
					)
				);

				HTL_Meta_Boxes_Helper::price_input(
					array(
						'id'          => '_sale_price',
						'value'       => get_post_meta( $thepostid, '_sale_price', true ),
						'label'       => esc_html__( 'Sale price:', 'wp-hotelier' ),
						'description' => 'Same price for all days of the week.',
					)
				);
				?>
			</div>

			<div class="htl-ui-setting-conditional htl-ui-setting-conditional--price-type" data-type="per_day">
				<?php
				HTL_Meta_Boxes_Helper::price_per_day(
					array(
						'id'          => '_regular_price_day',
						'value'       => get_post_meta( $thepostid, '_regular_price_day', true ),
						'label'       => esc_html__( 'Regular price:', 'wp-hotelier' ),
						'description' => 'The regular price of the room per day.',
					)
				);

				HTL_Meta_Boxes_Helper::price_per_day(
					array(
						'id'          => '_sale_price_day',
						'value'       => get_post_meta( $thepostid, '_sale_price_day', true ),
						'label'       => esc_html__( 'Sale price:', 'wp-hotelier' ),
						'description' => 'The sale price of the room per day.',
					)
				);
				?>
			</div>

			<div class="htl-ui-setting-conditional htl-ui-setting-conditional--price-type" data-type="seasonal_price">
				<?php
				HTL_Meta_Boxes_Views::seasonal_price(
					array(
						'default_price_input_name' => '_seasonal_base_price',
						'default_price_value'      => get_post_meta( $thepostid, '_seasonal_base_price', true ),
						'schema_price_input_name'  => '_seasonal_price',
						'schema_price_value'       => get_post_meta( $thepostid, '_seasonal_price', true ),
					)
				);
				?>
			</div>

			<?php
			/**
			 * A filter is provided to allow extensions to add their own price settings
			 */
			do_action( 'hotelier_room_price_settings_standard', self::get_price_placeholder() ); ?>
		</div>

		<?php do_action( 'hotelier_room_standard_settings_after_price' ); ?>

		<div class="htl-ui-setting-group htl-ui-setting-group--metabox htl-ui-setting-group--deposit-options">
			<?php
			HTL_Meta_Boxes_Helper::switch_input(
				array(
					'id'                => '_require_deposit',
					'value'             => get_post_meta( $thepostid, '_require_deposit', true ),
					'label'             => esc_html__( 'Require deposit?', 'wp-hotelier' ),
					'options'           => array(
						'yes' => esc_html__( 'Yes', 'wp-hotelier' ),
						'no'  => esc_html__( 'No', 'wp-hotelier' ),
					),
					'std'               => 'no',
					'checkbox-fallback' => true,
					'show-if'           => 'yes',
					'show-element'      => 'deposit-settings',
					'description'       => esc_html__( 'When enabled, a deposit is required at the time of booking.', 'wp-hotelier' )
				)
			);
			?>

			<div class="htl-ui-setting-conditional htl-ui-setting-conditional--deposit-settings" data-type="deposit-settings">
				<?php
				HTL_Meta_Boxes_Helper::select_input(
					array(
						'id'      => '_deposit_amount',
						'value'   => get_post_meta( $thepostid, '_deposit_amount', true ),
						'label'   => esc_html__( 'Deposit amount:', 'wp-hotelier' ),
						'options' => self::get_deposit_options(),
						'class'   => 'deposit-amount-select',
					)
				);
				?>

				<?php
				/**
				 * A filter is provided to allow extensions to add their own deposit options
				 */
				do_action( 'hotelier_room_standard_deposit_options' ); ?>
			</div>
		</div>

		<?php
		HTL_Meta_Boxes_Helper::switch_input(
			array(
				'id'                => '_non_cancellable',
				'value'             => get_post_meta( $thepostid, '_non_cancellable', true ),
				'label'             => esc_html__( 'Non cancellable?', 'wp-hotelier' ),
				'options'           => array(
					'yes' => esc_html__( 'Yes', 'wp-hotelier' ),
					'no'  => esc_html__( 'No', 'wp-hotelier' ),
				),
				'std'               => 'no',
				'checkbox-fallback' => true,
				'description'       => esc_html__( 'When enabled, reservations that include this room will be non cancellable and non refundable.', 'wp-hotelier' )
			)
		);

		HTL_Meta_Boxes_Helper::multi_text(
			array(
				'id'           => '_room_conditions',
				'value'        => get_post_meta( $thepostid, '_room_conditions', true ),
				'label'        => esc_html__( 'Conditions:', 'wp-hotelier' ),
				'placeholder'  => esc_html__( 'Special condition here', 'wp-hotelier' ),
				'button_label' => esc_html__( 'Add new condition', 'wp-hotelier' ),
			)
		);
		?>

		<?php
		/**
		 * A filter is provided to allow extensions to add their own standard room settings
		 */
		do_action( 'hotelier_room_standard_settings' ); ?>
	</div>

	<div class="room-settings__variations">
		<h3 class="htl-ui-heading htl-ui-heading--section-header"><?php esc_html_e( 'Variable room settings', 'wp-hotelier' ); ?></h3>

		<?php HTL_Meta_Boxes_Views::variations_toolbar( 'top' ); ?>

		<?php
		$get_room_rates = get_terms( 'room_rate', 'hide_empty=0' );

		if ( ! empty( $get_room_rates ) && ! is_wp_error( $get_room_rates ) ) : ?>

			<div class="room-variations__list">

				<div class="room-variation room-variation--placeholder htl-ui-settings-wrap" data-key="999999999999999999">

					<?php HTL_Meta_Boxes_Views::variation_header( array(), $get_room_rates, 999999999999999999 ); ?>

					<?php HTL_Meta_Boxes_Views::variation_content( array(), 999999999999999999 ); ?>

				</div>

				<?php
				$variations  = maybe_unserialize( get_post_meta( $thepostid, '_room_variations', true ) );
				$loop_lenght = $variations ? count( $variations ) : 1;

				for ( $loop = 1; $loop <= $loop_lenght; $loop++ ) : ?>

					<div class="room-variation room-variation--in-use htl-ui-settings-wrap" data-key="<?php echo absint( $loop ); ?>">

						<?php HTL_Meta_Boxes_Views::variation_header( $variations, $get_room_rates, $loop ); ?>

						<?php HTL_Meta_Boxes_Views::variation_content( $variations, $loop ); ?>

					</div>

				<?php endfor; ?>
			</div>

		<?php else : ?>

			<?php
			$no_rates_notice = sprintf( wp_kses( __( 'Before adding variations, add and save some room rates first. You can create a room rate <a href="%1$s">here</a>. Then edit again this room to add your variations.', 'wp-hotelier' ), array( 'a' => array( 'href' => array() ) ) ), 'edit-tags.php?taxonomy=room_rate&post_type=room' );

			htl_ui_print_notice( $no_rates_notice );
			?>

		<?php endif; ?>

		<?php HTL_Meta_Boxes_Views::variations_toolbar( 'bottom' ); ?>
	</div>
</div>
