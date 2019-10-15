<?php
/**
 * BuddyPress - Members Home
 *
 * @since   1.0.0
 * @version 3.0.0
 */
global $current_user;
?>

	<?php bp_nouveau_member_hook( 'before', 'home_content' ); ?>

	<div id="item-header" role="complementary" data-bp-item-id="<?php echo esc_attr( bp_displayed_user_id() ); ?>" data-bp-item-component="members" class="users-header single-headers">
		<div id="red-bar-after-header"></div>
		<?php bp_nouveau_member_header_template_part(); ?>
		

	</div><!-- #item-header -->

	<div class="bp-wrap">

		<div class="profile-abouts">
			<ul class="profile-news-list">
				<li class="follows"><div class="follows-center"><div class="ws-badge">
				<?php
				if ( function_exists( 'bp_follow_total_follow_counts' ) ) {
					$count = bp_follow_total_follow_counts( bp_displayed_user_id() );
				} echo $count['followers'];
				?>
				</div><span>Followers</span></div></li>
				<li class="following"><div class="following-center"><div class="ws-badge">
				<?php
				if ( function_exists( 'bp_follow_total_follow_counts' ) ) {
					$count = bp_follow_total_follow_counts( bp_displayed_user_id() );
				} echo $count['following'];
				?>
				</div><span>Following</span></div></li>
					<li class="connect-button" id="connect-btn"><div id="profile-menu-anchor"><span>Menu</span>
						<?php
						$counter       = 0;
						$notifications = bp_notifications_get_notifications_for_user( bp_loggedin_user_id(), 'object' );
						if ( $notifications ) :
							foreach ( $notifications as $notice ) :
									$counter++;
							endforeach;
						endif;
						?>
						<span class="notices"><?php echo $counter; ?></span></div>
							<?php bp_nav_menu( array( 'container_id' => 'profile-menu' ) ); ?>
					</li>
			</ul> <!-- profile-news-list -->

			<ul class="profile-section-list">
				<li class="profile-info">
					<div id="item-header-content">
						<?php if ( bp_nouveau_member_has_meta() ) : ?>
							<div class="item-meta">

								<?php bp_nouveau_member_meta(); ?>
							</div><!-- #item-meta -->
						<?php endif; ?>

						<?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
							<h2 class="user-nicename">@<?php bp_displayed_user_mentionname(); ?></h2>
							<h2 class="user-fullname"><?php bp_displayed_user_fullname(); ?></h2>
						<?php endif; ?>
							<?php
							if ( bp_displayed_user_id() === $current_user->ID && ! empty( pmpro_affiliates_getAffiliatesForUser( $current_user->ID )[0]->code ) ) :
								$url    = get_site_url();
								$code   = ( ! empty( pmpro_affiliates_getAffiliatesForUser( $current_user->ID )[0]->code ) ) ? pmpro_affiliates_getAffiliatesForUser( $current_user->ID )[0]->code : '';
								$sub_id = ( ! empty( pmpro_affiliates_getAffiliatesForUser( $current_user->ID )[0]->trackingcode ) ) ? '&subid=' . pmpro_affiliates_getAffiliatesForUser( $current_user->ID )[0]->trackingcode : '';
								?>
							<div class="affiliate-link-tab member-header-actions action">
								<h2 class="affiliate-title">Your Affiliate Link</h2>
								<div class="affiliate-link">
									<a class="affiliate-copy-link"><?php _e( "$url/membership-levels/?pa=$code$sub_id", 'x' ); ?></a>
								</div>
								<div class="affiliate-copy-btn-wrap generic-button"><a class="x-btn x-btn-large x-btn-block" data-toggle="tooltip" data-placement="top" title="Click to copy affiliate link">Copy Link <?php echo do_shortcode( '[x_icon type="copy"]' ); ?></a></div>
							</div>
						<?php endif; ?>
						<?php
						bp_nouveau_member_header_buttons(
							array(
								'container'         => 'div',
								'button_element'    => 'a',
								'container_classes' => array( 'member-header-actions' ),
							)
						);
						?>

						<?php
						if ( bp_displayed_user_id() === $current_user->ID ) :
							?>
							<div class="vr-room-form-btn member-header-actions action">
								<div class="generic-button">
									<a>Virtual Networking</a>
								</div>
							</div>
						<?php endif; ?>

						<?php bp_nouveau_member_hook( 'before', 'header_meta' ); ?>


					</div><!-- #item-header-content -->
				</li>
				<?php if ( ! empty( bp_get_profile_field_data( array( 'field' => 'Bio' ) ) ) ) : ?>
				<li class="profile-bio-item">
					<i class="fa fa-user-o" aria-hidden="true"></i>
					<div class="member-description">
							<blockquote class="member-bio">
								<?php echo bp_get_profile_field_data( array( 'field' => 'Bio' ) ); ?>
							</blockquote><!-- .member-bio -->
					</div><!-- .member-description -->
				</li> <!-- profile-bio-item -->
				<?php endif; ?>
				<?php if ( ! empty( bp_get_profile_field_data( array( 'field' => 'field_2' ) ) ) || ! empty( bp_get_profile_field_data( array( 'field' => 'field_3' ) ) ) ) : ?>
					<li class="contact contact-phone">
						<i class="fa fa-phone" aria-hidden="true"></i>
						<div class="contact-phone-wrap">
						<?php if ( ! empty( bp_get_profile_field_data( array( 'field' => 'field_2' ) ) ) ) : ?>
						<div class="mobile-number">
							<?php echo 'Mobile: ' . bp_get_profile_field_data( array( 'field' => 'field_2' ) ); ?>
						</div>
						<?php endif; ?>
						<?php if ( ! empty( bp_get_profile_field_data( array( 'field' => 'field_3' ) ) ) ) : ?>
						<div class="work-number">
							<?php echo 'Work: ' . bp_get_profile_field_data( array( 'field' => 'field_3' ) ); ?>
						</div>
						<?php endif; ?>
					</div> <!-- contact-phone-wrap -->
					</li> <!-- phone-numbers -->
				<?php endif; ?>
				<?php if ( ! empty( bp_get_profile_field_data( array( 'field' => 'Personal Email' ) ) ) || ! empty( bp_get_profile_field_data( array( 'field' => 'Work Email' ) ) ) ) : ?>
				<li class="contact contact-email">
					<i class="fa fa-envelope-o" aria-hidden="true"></i>
					<div class="contact-email-wrap">
					<?php if ( ! empty( bp_get_profile_field_data( array( 'field' => 'Personal Email' ) ) ) ) : ?>
					<div class="personal-email">
						<?php echo 'Personal Email: ' . bp_get_profile_field_data( array( 'field' => 'Personal Email' ) ); ?>
					</div>
					<?php endif; ?>
					<?php if ( ! empty( bp_get_profile_field_data( array( 'field' => 'Work Email' ) ) ) ) : ?>
					<div class="work-email">
						<?php echo 'Work Email: ' . bp_get_profile_field_data( array( 'field' => 'Work Email' ) ); ?>
					</div>
					<?php endif; ?>
				</div> <!-- contact-email-wrap -->
				</li> <!-- email-numbers -->
				<?php endif; ?>
				<?php if ( ! empty( bp_get_profile_field_data( array( 'field' => 'Personal Interests' ) ) ) ) : ?>
				<li class="contact profile-skills">
					<i class="fa fa-info-circle" aria-hidden="true"></i>
					<div class="profile-skills-div">
					</div>
				</li> <!-- email-numbers -->
				<?php endif; ?>
			</ul> <!-- profile-bio -->
		</div> <!-- profile-abouts -->

		<div id="item-body" class="item-body">
			<?php bp_nouveau_member_template_part(); ?>

		</div><!-- #item-body -->
		<?php
		if ( bp_displayed_user_id() === $current_user->ID ) :
			?>
			<div id="virtual-modal">
					<div class="virtual-modal-container">
						<div class="virtual-modal-title"><h2 class="text-center">Welcome to Rockstar VR Area</h2><a class="virtual-modal-close">X</a></div>
						<div class="col-conference col-create-conference text-center">
							<h4 class="conference-title create-conference text-center">Virtual Conference</h4>

							<div class="generic-button">
								<a type="button" class="create-conference-btn"><?php esc_html_e( 'Create Conference', 'x-child' ); ?></a>
							</div>
							<div id="create-conference-form-wraper">
								<?php echo gravity_form( 'Virtual Conference', false, false, false, null, true, 0, false ); ?>
							</div>

						</div>
						<div class="col-conference col-join-conference text-center">
							<h4 class="conference-title join-conference text-center">Join Virtual Conference</h4>

							<div class="generic-button">
								<a type="button" class="join-conference-btn"><?php esc_html_e( 'Join Conference', 'x-child' ); ?></a>
							</div>
							<div id="join-conference-form-wraper">
								<?php echo gravity_form( 'Join Conference', false, false, false, null, true, 0, false ); ?>
							</div>

						</div>

					</div>
			</div>
		<?php endif; ?>

		
	</div><!-- .bp-wrap -->

	<?php bp_nouveau_member_hook( 'after', 'home_content' ); ?>
