<?php
/**
 * BuddyPress Custom Members Widget.
 *
 * @package x-child
 * @subpackage MembersWidgets
 * @since 1.0.3
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Members Widget.
 *
 * @since 1.0.3
 */
class BP_Custom_Members_Widget extends WP_Widget {

	/**
	 * Constructor method.
	 *
	 * @since 1.5.0
	 */
	public function __construct() {

		// Setup widget name & description.
		$name        = _x( '(Wonka) Suggested Members', 'widget name', 'buddypress' );
		$description = __( 'A dynamic list of suggested members active, popular, and newest members', 'buddypress' );

		// Call WP_Widget constructor.
		parent::__construct( false, $name, array(
			'description'                 => $description,
			'classname'                   => 'widget-bp-custom-members-widget widget',
			'customize_selective_refresh' => true,
		) );
	}

	/**
	 * This function returns a custom query of members according to the skillset of the current user
	 * @param  string $field_name  This is for setting the field skillset
	 * @param  array $field_value This is for the skillsets that will be queried
	 * @return query args
	 *
	 * @since 3.5
	 */
	public function custom_skillset_query( $field_name = '', $field_value = array() ) {
		if ( empty( $field_name ) )
		    return '';
		  
		  global $wpdb;

		  $field_id = xprofile_get_field_id_from_name( $field_name ); 
		 
		  if ( !empty( $field_id ) ) :
		    $query = "SELECT user_id FROM " . $wpdb->prefix . "bp_xprofile_data WHERE field_id = " . $field_id;
		  else :
		   return '';
		  endif;
		  
		  if ( $field_value != '' ) :
		  	foreach ($field_value as $key => $value ) :
		  		if ( $key === 0 ) :
		    		$query .= " AND value LIKE '%" . $value . "%'";
		    	else :
		    		$query .= " OR value LIKE '%" . $value . "%'";
		    	endif;
		  	endforeach;
		      /* 
		      LIKE is slow. If you're sure the value has not been serialized, you can do this:
		      $query .= " AND value = '" . $field_value . "'";
		      */
	      endif;
		  
		  $custom_ids = $wpdb->get_col( $query );
		  
		  if ( !empty( $custom_ids ) ) {
		    // convert the array to a csv string
		    $custom_ids_str = implode(",", $custom_ids);
		    return $custom_ids_str;
		  }
		  else
		   return '';
	}

	public function custom_has_members( $args = '' ) {
		global $members_template;

		// Default user ID.
		$user_id = 0;

		// User filtering.
		if ( bp_is_user_friends() && ! bp_is_user_friend_requests() ) {
			$user_id = bp_displayed_user_id();
		}

		$member_type = bp_get_current_member_type();
		if ( ! $member_type && ! empty( $_GET['member_type'] ) ) {
			if ( is_array( $_GET['member_type'] ) ) {
				$member_type = $_GET['member_type'];
			} else {
				// Can be a comma-separated list.
				$member_type = explode( ',', $_GET['member_type'] );
			}
		}

		$search_terms_default = null;
		$search_query_arg = bp_core_get_component_search_query_arg( 'members' );
		if ( ! empty( $_REQUEST[ $search_query_arg ] ) ) {
			$search_terms_default = stripslashes( $_REQUEST[ $search_query_arg ] );
		}

		$type = ( isset( $args['type'] ) ) ? $args['type']: 'active';
		$per_page = ( isset( $args['per_page'] ) ) ? $args['per_page']: 20;
		$max = ( isset( $args['max'] ) ) ? $args['max']: false;
		$include = ( isset( $args['include'] ) ) ? $args['include']: false;
		$exclude = ( isset( $args['exclude'] ) ) ? $args['exclude']: false;
		// Type: active ( default ) | random | newest | popular | online | alphabetical.
		$r = bp_parse_args( $args, array(
			'type'                => $type,
			'page'                => 1,
			'per_page'            => $per_page,
			'max'                 => $max,

			'page_arg'            => 'upage',  // See https://buddypress.trac.wordpress.org/ticket/3679.

			'include'             => $include,    // Pass a user_id or a list (comma-separated or array) of user_ids to only show these users.
			'exclude'             => $exclude,    // Pass a user_id or a list (comma-separated or array) of user_ids to exclude these users.

			'user_id'             => $user_id, // Pass a user_id to only show friends of this user.
			'member_type'         => $member_type,
			'member_type__in'     => '',
			'member_type__not_in' => '',
			'search_terms'        => $search_terms_default,

			'meta_key'            => false,    // Only return users with this usermeta.
			'meta_value'          => false,    // Only return users where the usermeta value matches. Requires meta_key.

			'populate_extras'     => true,      // Fetch usermeta? Friend count, last active etc.
		));

		// Pass a filter if ?s= is set.
		if ( is_null( $r['search_terms'] ) ) {
			if ( !empty( $_REQUEST['s'] ) ) {
				$r['search_terms'] = $_REQUEST['s'];
			} else {
				$r['search_terms'] = false;
			}
		}

		// Set per_page to max if max is larger than per_page.
		if ( !empty( $r['max'] ) && ( $r['per_page'] > $r['max'] ) ) {
			$r['per_page'] = $r['max'];
		}

		// Query for members and populate $members_template global.
		$members_template = new BP_Core_Members_Template(
			$r['type'],
			$r['page'],
			$r['per_page'],
			$r['max'],
			$r['user_id'],
			$r['search_terms'],
			$r['include'],
			$r['populate_extras'],
			$r['exclude'],
			$r['meta_key'],
			$r['meta_value'],
			$r['page_arg'],
			$r['member_type'],
			$r['member_type__in'],
			$r['member_type__not_in']
		);

		bp_follow_inject_member_follow_status( $members_template );

		/**
		 * Filters whether or not BuddyPress has members to iterate over.
		 *
		 * @since 1.2.4
		 * @since 2.6.0 Added the `$r` parameter
		 *
		 * @param bool  $value            Whether or not there are members to iterate over.
		 * @param array $members_template Populated $members_template global.
		 * @param array $r                Array of arguments passed into the BP_Core_Members_Template class.
		 */
		return apply_filters( 'custom_has_members', $members_template->has_members(), $members_template, $r );
	}

	/**
	 * Display the Members widget.
	 *
	 * @since 1.0.3
	 *
	 * @see WP_Widget::widget() for description of parameters.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Widget settings, as saved by the user.
	 */
	public function widget( $args, $instance ) {
		global $members_template, $current_user, $wpdb;

		// Get widget settings.
		$settings = $this->parse_settings( $instance );

		/**
		 * Filters the title of the Members widget.
		 *
		 * @since 1.8.0
		 * @since 2.3.0 Added 'instance' and 'id_base' to arguments passed to filter.
		 *
		 * @param string $title    The widget title.
		 * @param array  $settings The settings for the particular instance of the widget.
		 * @param string $id_base  Root ID for all widgets of this type.
		 */
		$title = apply_filters( 'widget_title', $settings['title'], $settings, $this->id_base );
		$title = $settings['link_title'] ? '<a href="' . bp_get_members_directory_permalink() . '">' . $title . '</a>' : $title;

		/**
		 * Filters the separator of the member widget links.
		 *
		 * @since 2.4.0
		 *
		 * @param string $separator Separator string. Default '|'.
		 */
		$separator = apply_filters( 'bp_members_widget_separator', '|' );


		$user_skills_key = 'skillset';

		$skillsets =  xprofile_get_field_data( 'skillset', $current_user->ID );
		if ( $skillsets === '') :
			$include = false;
		else :
			$skillsets = strtolower( $skillsets );
			$skillsets = str_replace( ' ', '', $skillsets );
			$skillsets = explode( ',', $skillsets );

			$include = $this->custom_skillset_query( 'skillset', $skillsets );
			$include = explode( ',', $include );
			$unset_key = array_search( $current_user->ID, $include );
			unset( $include[$unset_key] );
		endif;

        $alreadyConnectUsers = $wpdb->get_results("SELECT initiator_user_id FROM wp_bp_friends WHERE friend_user_id = ".$current_user->ID);

        $alreadyConnectUsers1 = $wpdb->get_results("SELECT friend_user_id FROM wp_bp_friends WHERE initiator_user_id = ".$current_user->ID);
		$exclude_user = array(bp_displayed_user_id(), $current_user->ID);
		foreach ($alreadyConnectUsers as $key => $value) {
			$exclude_user[] = $value->initiator_user_id;
		}
		foreach ($alreadyConnectUsers1 as $key => $value) {
			$exclude_user[] = $value->friend_user_id;
		}
		// Setup args for querying members.
		$members_args = array(
			'user_id'         => 0,
			'type'            => $settings['member_default'],
			'per_page'        => $settings['max_members'],
			'max'             => $settings['max_members'],
			'include'		  => $include,
			'exclude'		  => $exclude_user,
			'populate_extras' => true,
			'search_terms'    => false,
		);

		// Back up the global.
		$old_members_template = $members_template;
		if ( is_page('members') ) :
		/*==================================================================================
		=            This is for the widget being displayed on the members page            =
		==================================================================================*/
		?>
		<?php // Output before widget HTMl, title (and maybe content before & after it).
		echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title']; ?>

		<?php if ( bp_has_members( $members_args )  && $include !== false ) : ?>

			<div class="item-description" id="members-list-description">
				<span class="description-text"><small>Suggested members matched with similar skillsets from your profile</small></span>
			</div>

			<ul id="members-list" class="widget-bp-custom-members-widget-item-list item-list" aria-live="polite" aria-relevant="all" aria-atomic="true">

				<?php while ( bp_members() ) : bp_the_member(); ?>

					<li class="vcard">
						<?php if ( bp_get_user_has_avatar( bp_get_member_user_id() ) ) : ?>
							<div class="item-avatar">
								<a href="<?php bp_member_permalink() ?>" class="suggested-avatar"><?php bp_member_avatar(); ?></a>
							</div>
							
						<?php endif; ?>

						<div class="item">
							<div class="item-title fn"><a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a></div>
							<div class="item-meta">
								<span class="current-title"><?php
									echo xprofile_get_field_data( 'Job Title 1', bp_get_member_user_id() ); ?></span>
							</div>
						</div>
						<div class="action">
							
							<?php bp_add_friend_button( bp_get_member_user_id() ); ?>
							<?php do_action( 'bp_directory_members_actions' ); ?>

						</div>
					</li>

				<?php endwhile; ?>

			</ul>

			<?php wp_nonce_field( 'bp_core_widget_members', '_wpnonce-members', false ); ?>

			<input type="hidden" name="members_widget_max" id="members_widget_max" value="<?php echo esc_attr( $settings['max_members'] ); ?>" />

		<?php else: ?>
			<?php if ( $include === false ) : ?>
				<div class="widget-error">
					<?php esc_html_e( 'You have no skillsets set in your profile! ', 'buddypress' ); ?><span><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Edit your profile by adding skillsets to the skillset field in your profile."></i></span>
				</div>
			<?php else : ?>
				<div class="widget-error">
					<?php esc_html_e( 'There seems to be no other members with matching skillsets. ', 'buddypress' ); ?><span><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="This message is being set due to no other members having matching skillsets with you."></i></span>
				</div>
			<?php endif; ?>

		<?php endif; ?>

		<?php echo $args['after_widget'];
		/*=====  End of This is for the widget being displayed on the members page  ======*/
		else : 
		/*=====================================================================================
		=            This is for the widget being displayed on single profile page            =
		=====================================================================================*/
		?>
		<?php // Output before widget HTMl, title (and maybe content before & after it).
		echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title']; ?>

			<?php if ( $this->custom_has_members( $members_args ) && $include !== false ) : ?>


				<div class="item-description" id="members-list-description">
					<span class="description-text"><small>Suggested members matched with similar skillsets from your profile</small></span>
				</div>

				<ul id="members-list" class="widget-bp-custom-members-widget-item-list item-list" aria-live="polite" aria-relevant="all" aria-atomic="true">

					<?php while ( bp_members() ) : bp_the_member(); ?>

						<li class="vcard">
							<?php if ( bp_get_user_has_avatar( bp_get_member_user_id() ) ) : ?>
								<div class="item-avatar">
									<a href="<?php bp_member_permalink() ?>" class="suggested-avatar"><?php bp_member_avatar(); ?></a>
								</div>
								
							<?php endif; ?>

							<div class="item">
								<div class="item-title fn"><a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a></div>
								<div class="item-meta">
									<span class="current-title"><?php
										echo xprofile_get_field_data( 'Job Title 1', bp_get_member_user_id() ); ?></span>
								</div>
							</div>
							<div class="action">
								
								<?php bp_add_friend_button( bp_get_member_user_id() ); ?>
								<?php do_action( 'bp_directory_members_actions' ); ?>

							</div>
						</li>

					<?php endwhile; ?>

				</ul>

				<?php wp_nonce_field( 'bp_core_widget_members', '_wpnonce-members', false ); ?>

				<input type="hidden" name="members_widget_max" id="members_widget_max" value="<?php echo esc_attr( $settings['max_members'] ); ?>" />

			<?php else: ?>
				<?php if ( $include === false ) : ?>
					<div class="widget-error">
						<?php esc_html_e( 'You have no skillsets set in your profile! ', 'buddypress' ); ?><span><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Edit your profile by adding skillsets to the skillset field in your profile."></i></span>
					</div>
				<?php else : ?>
					<div class="widget-error">
						<?php esc_html_e( 'There seems to be no other members with matching skillsets. ', 'buddypress' ); ?><span><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="This message is being set due to no other members having matching skillsets with you."></i></span>
					</div>
				<?php endif; ?>

			<?php endif; ?>

			<?php echo $args['after_widget'];
		/*=====  End of This is for the widget being displayed on single profile page  ======*/
		endif;

		// Restore the global.
		$members_template = $old_members_template;
	}

	/**
	 * Update the Members widget options.
	 *
	 * @since 1.0.3
	 *
	 * @param array $new_instance The new instance options.
	 * @param array $old_instance The old instance options.
	 * @return array $instance The parsed options to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']          = strip_tags( $new_instance['title'] );
		$instance['max_members']    = strip_tags( $new_instance['max_members'] );
		$instance['member_default'] = strip_tags( $new_instance['member_default'] );
		$instance['link_title']	    = (bool) $new_instance['link_title'];

		return $instance;
	}

	/**
	 * Output the Members widget options form.
	 *
	 * @since 1.0.3
	 *
	 * @param array $instance Widget instance settings.
	 * @return void
	 */
	public function form( $instance ) {

		// Get widget settings.
		$settings       = $this->parse_settings( $instance );
		$title          = strip_tags( $settings['title'] );
		$max_members    = strip_tags( $settings['max_members'] );
		$member_default = strip_tags( $settings['member_default'] );
		$link_title     = (bool) $settings['link_title']; ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php esc_html_e( 'Title:', 'buddypress' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" style="width: 100%" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'link_title' ) ?>">
				<input type="checkbox" name="<?php echo $this->get_field_name( 'link_title' ) ?>" id="<?php echo $this->get_field_id( 'link_title' ) ?>" value="1" <?php checked( $link_title ) ?> />
				<?php esc_html_e( 'Link widget title to Members directory', 'buddypress' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'max_members' ); ?>">
				<?php esc_html_e( 'Max members to show:', 'buddypress' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'max_members' ); ?>" name="<?php echo $this->get_field_name( 'max_members' ); ?>" type="text" value="<?php echo esc_attr( $max_members ); ?>" style="width: 30%" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'member_default' ) ?>"><?php esc_html_e( 'Default members to show:', 'buddypress' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'member_default' ) ?>" id="<?php echo $this->get_field_id( 'member_default' ) ?>">
				<option value="newest"  <?php if ( 'newest'  === $member_default ) : ?>selected="selected"<?php endif; ?>><?php esc_html_e( 'Newest',  'buddypress' ); ?></option>
				<option value="active"  <?php if ( 'active'  === $member_default ) : ?>selected="selected"<?php endif; ?>><?php esc_html_e( 'Active',  'buddypress' ); ?></option>
				<option value="popular" <?php if ( 'popular' === $member_default ) : ?>selected="selected"<?php endif; ?>><?php esc_html_e( 'Popular', 'buddypress' ); ?></option></option>
			</select>
		</p>

	<?php
	}

	/**
	 * Merge the widget settings into defaults array.
	 *
	 * @since 2.3.0
	 *
	 *
	 * @param array $instance Widget instance settings.
	 * @return array
	 */
	public function parse_settings( $instance = array() ) {
		return bp_parse_args( $instance, array(
			'title' 	     => __( 'Suggested Members', 'buddypress' ),
			'max_members' 	 => 5,
			'member_default' => 'newest',
			'link_title' 	 => false
		), 'custom_members_widget_settings' );
	}
}
