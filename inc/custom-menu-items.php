<?php
/*s
 * Wonka_menu_on_admin_screen
 *
 * @package x-child
 * @subpackage x_child
 * @since 1.0.0
 * @author Carlos
 */

// Removes the BuddyPress metabox in the WP Nav Menu Admin UI .line 153.
remove_action( 'load-nav-menus.php', 'bp_admin_wp_nav_menu_meta_box' );

function wonka_menu_on_admin_screen() {
	if ( ! bp_is_root_blog() ) {
		return;
	}

	add_meta_box( 'wonka-add-buddypress-nav-menu', __( 'Rockstar Community Menu', 'buddypress' ), 'wonka_bp_admin_do_wp_nav_menu_meta_box', 'nav-menus', 'side', 'default' );

	add_action( 'admin_print_footer_scripts', 'bp_admin_wp_nav_menu_restrict_items' );

}
add_action( 'admin_init', 'wonka_menu_on_admin_screen' );

/**
 * Build and populate the BuddyPress accordion on Appearance > Menus.
 *
 * @since 1.0.0
 *
 * @global $nav_menu_selected_id
 */
function wonka_bp_admin_do_wp_nav_menu_meta_box() {
	global $nav_menu_selected_id;

	$walker = new BP_Walker_Nav_Menu_Checklist( false );
	$args   = array( 'walker' => $walker );

	$post_type_name = 'buddypress';

	$tabs = array();

	$tabs['loggedin']['label'] = __( 'Logged-In', 'buddypress' );
	$tabs['loggedin']['pages'] = wonka_bp_nav_menu_get_loggedin_pages();

	$tabs['loggedout']['label'] = __( 'Logged-Out', 'buddypress' );
	$tabs['loggedout']['pages'] = wonka_bp_nav_menu_get_loggedout_pages();

	?>

	<div id="buddypress-menu" class="posttypediv">
		<h4><?php _e( 'Logged-In', 'buddypress' ); ?></h4>
		<p><?php _e( '<em>Logged-In</em> links are relative to the current user, and are not visible to visitors who are not logged in.', 'buddypress' ); ?></p>

		<div id="tabs-panel-posttype-<?php echo $post_type_name; ?>-loggedin" class="tabs-panel tabs-panel-active">
			<ul id="buddypress-menu-checklist-loggedin" class="categorychecklist form-no-clear">
				<?php echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $tabs['loggedin']['pages'] ), 0, (object) $args ); ?>
			</ul>
		</div>

		<h4><?php _e( 'Logged-Out', 'buddypress' ); ?></h4>
		<p><?php _e( '<em>Logged-Out</em> links are not visible to users who are logged in.', 'buddypress' ); ?></p>

		<div id="tabs-panel-posttype-<?php echo $post_type_name; ?>-loggedout" class="tabs-panel tabs-panel-active">
			<ul id="buddypress-menu-checklist-loggedout" class="categorychecklist form-no-clear">
				<?php echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $tabs['loggedout']['pages'] ), 0, (object) $args ); ?>
			</ul>
		</div>

		<?php
		$removed_args = array(
			'action',
			'customlink-tab',
			'edit-menu-item',
			'menu-item',
			'page-tab',
			'_wpnonce',
		);
		?>

		<p class="button-controls">
			<span class="list-controls">
				<a href="
				<?php
				echo esc_url(
					add_query_arg(
						array(
							$post_type_name . '-tab' => 'all',
							'selectall'              => 1,
						),
						remove_query_arg( $removed_args )
					)
				);
				?>
					#buddypress-menu" class="select-all"><?php _e( 'Select All', 'buddypress' ); ?></a>
			</span>
			<span class="add-to-menu">
				<input type="submit"<?php if ( function_exists( 'wp_nav_menu_disabled_check' ) ) : wp_nav_menu_disabled_check( $nav_menu_selected_id ); endif; ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu', 'buddypress' ); ?>" name="add-custom-menu-item" id="submit-buddypress-menu" />
				<span class="spinner"></span>
			</span>
		</p>
	</div><!-- /#buddypress-menu -->

	<?php
}


/** Nav Menu ******************************************************************/

/**
 * Create fake "post" objects for BP's logged-in nav menu for use in the WordPress "Menus" settings page.
 *
 * WordPress nav menus work by representing post or tax term data as a custom
 * post type, which is then used to populate the checkboxes that appear on
 * Dashboard > Appearance > Menu as well as the menu as rendered on the front
 * end. Most of the items in the BuddyPress set of nav items are neither posts
 * nor tax terms, so we fake a post-like object so as to be compatible with the
 * menu.
 *
 * This technique also allows us to generate links dynamically, so that, for
 * example, "My Profile" will always point to the URL of the profile of the
 * logged-in user.
 *
 * @since 1.9.0
 *
 * @return mixed A URL or an array of dummy pages.
 */
function wonka_bp_nav_menu_get_loggedin_pages() {
	$bp = buddypress();
	$component = 'members';
	$menu = array();
	foreach ( $bp->{$component}->nav->get_item_nav() as $nav_menu ) {
		// Get the correct menu link. See https://buddypress.trac.wordpress.org/ticket/4624.
		$link = bp_loggedin_user_domain() ? str_replace( bp_loggedin_user_domain(), bp_displayed_user_domain(), $nav_menu->link ) : trailingslashit( bp_displayed_user_domain() . $nav_menu->link );

		// Add this menu.
		$menu         = new stdClass();
		$menu->class  = array( 'menu-parent' );
		$menu->css_id = $nav_menu->css_id;
		$menu->link   = $link;
		$menu->name   = $nav_menu->name;
		$menu->parent = 0;

		if ( ! empty( $nav_menu->children ) ) {
			$submenus = array();

			foreach ( $nav_menu->children as $sub_menu ) {
				$submenu = new stdClass;
				$submenu->class  = array( 'menu-child' );
				$submenu->css_id = $sub_menu->css_id;
				$submenu->link   = $sub_menu->link;
				$submenu->name   = $sub_menu->name;
				$submenu->parent = $nav_menu->slug;

				// If we're viewing this item's screen, record that we need to mark its parent menu to be selected.
				if ( bp_is_current_action( $sub_menu->slug ) && bp_is_current_component( $nav_menu->slug ) ) {
					$menu->class[]    = 'current-menu-parent';
					$submenu->class[] = 'current-menu-item';
				}

				$submenus[] = $submenu;
			}
		}

		$menus[] = $menu;

		if ( ! empty( $submenus ) ) {
			$menus = array_merge( $menus, $submenus );
		}
	}

	//Try to catch the cached version first.
	if ( ! empty( $bp->wp_nav_menu_items->loggedin ) ) {
		return $bp->wp_nav_menu_items->loggedin;
	}

	// Pull up a list of items registered in BP's primary nav for the member.
	$bp_menu_items = $bp->members->nav->get_primary();

	// Some BP nav menu items will not be represented in bp_nav, because
	// they are not real BP components. We add them manually here.
	$bp_menu_items[] = array(
		'name' => __( 'Log Out', 'buddypress' ),
		'slug' => 'logout',
		'link' => wp_logout_url(),
	);

	// If there's nothing to show, we're done.
	if ( count( $bp_menu_items ) < 1 ) {
		return false;
	}

	$page_args = array();

	foreach ( $menus as $bp_item ) {

		// Remove <span>number</span>.
		$item_name = _bp_strip_spans_from_title( $bp_item->name );
		$page_args[ strtolower( $bp_item->name ) ] = (object) array(
			'ID'             => -1,
			'post_title'     => $item_name,
			'post_author'    => 0,
			'post_date'      => 0,
			'post_excerpt'   => strtolower( $bp_item->name ),
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'comment_status' => 'closed',
			'guid'           => $bp_item->link,
		);
	}

	if ( empty( $bp->wp_nav_menu_items ) ) {
		buddypress()->wp_nav_menu_items = new stdClass();
	}

	$bp->wp_nav_menu_items->loggedin = $page_args;

	return $page_args;

}

/**
 * Create fake "post" objects for BP's logged-out nav menu for use in the WordPress "Menus" settings page.
 *
 * WordPress nav menus work by representing post or tax term data as a custom
 * post type, which is then used to populate the checkboxes that appear on
 * Dashboard > Appearance > Menu as well as the menu as rendered on the front
 * end. Most of the items in the BuddyPress set of nav items are neither posts
 * nor tax terms, so we fake a post-like object so as to be compatible with the
 * menu.
 *
 * @since 1.9.0
 *
 * @return mixed A URL or an array of dummy pages.
 */
function wonka_bp_nav_menu_get_loggedout_pages() {
	$bp = buddypress();

	// Try to catch the cached version first.
	if ( ! empty( $bp->wp_nav_menu_items->loggedout ) ) {
		return $bp->wp_nav_menu_items->loggedout;
	}

	$bp_menu_items = array();

	// Some BP nav menu items will not be represented in bp_nav, because
	// they are not real BP components. We add them manually here.
	$bp_menu_items[] = array(
		'name' => __( 'Log In', 'buddypress' ),
		'slug' => 'login',
		'link' => wp_login_url(),
	);

	// The Register page will not always be available (ie, when
	// registration is disabled).
	$bp_directory_page_ids = bp_core_get_directory_page_ids();

	if ( ! empty( $bp_directory_page_ids['register'] ) ) {
		$register_page = get_post( $bp_directory_page_ids['register'] );
		$bp_menu_items[] = array(
			'name' => $register_page->post_title,
			'slug' => 'register',
			'link' => get_permalink( $register_page->ID ),
		);
	}

	// If there's nothing to show, we're done.
	if ( count( $bp_menu_items ) < 1 ) {
		return false;
	}

	$menu_items = apply_filters( 'bp_get_nav_menu_items', $items );

	$page_args = array();

	foreach ( $bp_menu_items as $bp_item ) {
		$page_args[ $bp_item['slug'] ] = (object) array(
			'ID'             => -1,
			'post_title'     => $bp_item['name'],
			'post_author'    => 0,
			'post_date'      => 0,
			'post_excerpt'   => $bp_item['slug'],
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'comment_status' => 'closed',
			'guid'           => $bp_item['link']
		);
	}

	if ( empty( $bp->wp_nav_menu_items ) ) {
		$bp->wp_nav_menu_items = new stdClass();
	}

	$bp->wp_nav_menu_items->loggedout = $page_args;

	return $page_args;
}

