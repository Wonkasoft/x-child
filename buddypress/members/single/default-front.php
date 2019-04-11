<?php
/**
 * BP Nouveau Default user's front template.
 *
 * @since 3.0.0
 * @version 3.1.0
 */
?>

<div class="member-front-page">

	<?php if ( ! is_customize_preview() && bp_current_user_can( 'bp_moderate' ) && ! is_active_sidebar( 'sidebar-buddypress-members' ) ) : ?>

		<div class="bp-feedback custom-homepage-info info">
			<strong><?php esc_html_e( 'Manage the members default front page', 'buddypress' ); ?></strong>
			<button type="button" class="bp-tooltip" data-bp-tooltip="<?php echo esc_attr_x( 'Close', 'button', 'buddypress' ); ?>" aria-label="<?php esc_attr_e( 'Close this notice', 'buddypress' ); ?>" data-bp-close="remove"><span class="dashicons dashicons-dismiss" aria-hidden="true"></span></button><br/>
			<?php
			printf(
				esc_html__( 'You can set the preferences of the %1$s or add %2$s to it.', 'buddypress' ),
				bp_nouveau_members_get_customizer_option_link(),
				bp_nouveau_members_get_customizer_widgets_link()
			);
			?>
		</div>

	<?php endif; ?>
	<?php if ( !empty( bp_get_profile_field_data( array( 'field' => 'Company 1' ) ) ) ) : ?>
	<div class="profile-details workexp">
		<i class="fa fa-briefcase" aria-hidden="true"><h4>Work Experience</h4></i>
		<div class="workexp-container">
			<span><?php echo bp_get_profile_field_data( array( 'field' => 'field_11' ) ); ?> @ <?php echo bp_get_profile_field_data( array( 'field' => 'Company 1' ) ); ?></span>
			<p><?php echo bp_get_profile_field_data( array( 'field' => 'xprofile_textarea_12' ) ); ?>
			</p>
			<span><?php echo bp_get_profile_field_data( array( 'field' => 'field_11' ) ); ?> @ <?php echo bp_get_profile_field_data( array( 'field' => 'Job Title 1' ) ); ?></span>
			<p><?php echo bp_get_profile_field_data( array( 'field' => 'xprofile_textarea_12' ) ); ?>
			</p>
			<span><?php echo bp_get_profile_field_data( array( 'field' => 'field_11' ) ); ?> @ <?php echo bp_get_profile_field_data( array( 'field' => 'Description 1' ) ); ?></span>
			<p><?php echo bp_get_profile_field_data( array( 'field' => 'xprofile_textarea_12' ) ); ?>
			</p>
		</div>
	</div> <!-- profile-details -->
	<?php endif; ?>

	<?php if ( !empty( bp_get_profile_field_data( array( 'field' => 'Place of Study 1' ) ) ) ) : ?>
	<div class="profile-details education">
		<i class="fa fa-university" aria-hidden="true"><h4>Place of Study</h4></i>
		<div class="education-container">
			<span><?php echo bp_get_profile_field_data( array( 'field' => 'field_11-1' ) ); ?> @ <?php echo bp_get_profile_field_data( array( 'field' => 'Place of Study 1' ) ); ?></span>
			<p><?php echo bp_get_profile_field_data( array( 'field' => 'xprofile_textarea_12' ) ); ?>
			</p>
			<span><?php echo bp_get_profile_field_data( array( 'field' => 'field_11' ) ); ?> @ <?php echo bp_get_profile_field_data( array( 'field' => 'Field of Study 1' ) ); ?></span>
			<p><?php echo bp_get_profile_field_data( array( 'field' => 'xprofile_textarea_12' ) ); ?>
			</p>
			<span><?php echo bp_get_profile_field_data( array( 'field' => 'field_11' ) ); ?> @ <?php echo bp_get_profile_field_data( array( 'field' => 'Points of Study or Description 1' ) ); ?></span>
			<p><?php echo bp_get_profile_field_data( array( 'field' => 'xprofile_textarea_12' ) ); ?>
			</p>
		</div>
	</div> <!-- profile-details -->
	<?php endif; ?>

	<?php if ( !empty( bp_get_profile_field_data( array( 'field' => 'First Interest' ) ) ) ) : ?>
	<div class="profile-details personal-interest">
		<i class="fa fa-info" aria-hidden="true"><h4>Personal Interest</h4></i>
	</div> <!-- profile-details -->
	<?php endif; ?>

</div> <!-- member-front-page -->

<?php if ( is_active_sidebar( 'sidebar-buddypress-members' ) ) : ?>

	<div id="member-front-widgets" class="bp-sidebar bp-widget-area" role="complementary">
		
		<?php dynamic_sidebar( 'sidebar-buddypress-members' ); ?>
	</div><!-- .bp-sidebar.bp-widget-area -->

<?php endif; ?>