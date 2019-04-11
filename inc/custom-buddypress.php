<?php
/**
 *
 * This file has all buddypress custom functions
 *
 * @since 1.0.0
 * 
 */
defined( 'ABSPATH' ) || exit;

/*================================================================
=            Load specific files for custom functions            =
================================================================*/
require( dirname( __FILE__ ) . '/bp-custom-widgets.php');
require( dirname( __FILE__ ) . '/class-custom-bp-nav-menu.php');
require( dirname( __FILE__ ) . '/class-bp-custom-members-widget.php');
require( dirname( __FILE__ ) . '/class-bp-custom-whos-online-widget.php');
/*=====  End of Load specific files for custom functions  ======*/

/**
 * [custom_friendship_button description]
 * @param  [array] $button 
 * @param  [string] $leader_id  
 * @param  [string] $follower_id 
 * @return [array]              
 */
function custom_friendship_button( $button ) {
  if ( $button['id'] === 'not_friends' ) :
    $button['link_text'] = 'Connect';
  endif;
  
  if ( $button['id'] === 'is_friend' ) :
    $button['link_text'] = 'Disconnect';
  endif;

  if ( $button['id'] === 'awaiting_response' ) :
    $button['link_text'] = 'Connection Requested';
  endif;

  if ( $button['id'] === 'pending' ) :
    $button['link_text'] = 'Cancel Connection Request';
  endif;

  return $button;
}
add_filter( 'bp_get_add_friend_button', 'custom_friendship_button');

/**
 * [filter_bp_nav_menu_items description]
 * @param  [array] $items
 * @param  [array] $args  
 * @return [array]        
 */
function filter_bp_nav_menu_items( $items ) {
  $user_domain = bp_loggedin_user_domain();
  
  foreach ($items as $key => $item ) :
    if ( strpos( $item->link, $user_domain ) === false ) :
      $item->link = _x( $user_domain . $item->link, '__x__' );
    endif;
    if ( $item->name === 'View' ) :
      unset($items[$key]);
    endif;
    if ( $item->css_id === 'members-following' ) :
      unset($items[$key]);
    endif;
    if ( $item->css_id === 'members-followers' ) :
      $items[$key]->name = 'Followers';
      $items[$key]->parent = 'activity';
    endif;
    if ( $item->css_id === 'friends-my-friends' ) :
      $items[$key]->parent = 'activity-friends';
    endif;
    if ( $item->css_id === 'requests' ) :
      $items[$key]->parent = 'activity-friends';
    endif;
    if ( $item->css_id === 'friends' ) :
      unset($items[$key]);
    endif;
    if ( $item->name === 'Profile') :
      $item->name = __( "Edit Profile", '__x__' );
      $item->link = _x( $user_domain . "profile/edit", '__x__' );
    endif;
    if ( $item->name === 'Home' ) :
      $item->name = _x( "Profile", '__x__' );
      $item->link = _x( "$user_domain", '__x__' );
    endif;
  endforeach;
  $newitem = new stdClass();
  $newitem->class = array( 'menu-child' );
    $newitem->css_id =  'group-create';
    $newitem->link =  _x( "/groups/create/", '__x__' );
    $newitem->name =  'Create a Group';
    $newitem->parent = 'groups';
  $items[] = $newitem;
  $newitem2 = new stdClass();
  $newitem2->class = array( 'menu-child' );
    $newitem2->css_id =  'avatar-change';
    $newitem2->link =  _x( "$user_domain/profile/change-avatar/", '__x__' );
    $newitem2->name =  __( "Change Avatar", '__x__' );
    $newitem2->parent = 'xprofile';
  $items[] = $newitem2;
  $newitem3 = new stdClass();
  $newitem3->class = array( 'menu-child' );
    $newitem3->css_id =  'cover-image';
    $newitem3->link =  _x( "$user_domain/profile/change-cover-image/", '__x__' );
    $newitem3->name =  __( "Change Cover Image", '__x__' );
    $newitem3->parent = 'xprofile';
  $items[] = $newitem3;
  $newitem4 = new stdClass();
  $newitem4->class = array( 'menu-parent' );
    $newitem4->css_id =  'rsc-videos';
    $newitem4->link =  get_post_type_archive_link( 'rsc_videos' );
    $newitem4->name =  __( "Videos", '__x__' );
    $newitem4->parent = '0';
  $newitem5 = new stdClass();
  $newitem5->class = array( 'menu-parent' );
    $newitem5->css_id =  'rn-rooms';
    $newitem5->link =  _x( "/rsc-vroom/", '__x__' );
    $newitem5->name =  __( "VN Rooms", '__x__' );
    $newitem5->parent = '0';
  $newitem6 = new stdClass();
  $newitem6->class = array( 'menu-parent' );
    $newitem6->css_id =  'member-directory';
    $newitem6->link =  _x( bp_get_members_directory_permalink(), '__x__' );
    $newitem6->name =  __( "Directory", '__x__' );
    $newitem6->parent = '0';
  $items = array_unique( $items, SORT_REGULAR );
  array_splice( $items, 23, 0, array($newitem4, $newitem5, $newitem6 ) );

  return $items;
}
// add the filter 
add_filter( 'bp_get_nav_menu_items', 'filter_bp_nav_menu_items', 50 ); 

function wonka_bp_nav_menu( $args = array() ) {
  static $menu_id_slugs = array();

  $defaults = array(
    'after'           => '',
    'before'          => '',
    'container'       => 'div',
    'container_class' => '',
    'container_id'    => '',
    'depth'           => 0,
    'echo'            => true,
    'fallback_cb'     => false,
    'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
    'link_after'      => '',
    'link_before'     => '',
    'menu_class'      => 'menu',
    'menu_id'         => '',
    'walker'          => '',
  );
  $args = wp_parse_args( $args, $defaults );

  /**
   * Filters the parsed bp_nav_menu arguments.
   *
   * @since 1.7.0
   *
   * @param array $args Array of parsed arguments.
   */
  $args = apply_filters( 'bp_nav_menu_args', $args );
  $args = (object) $args;

  $items = $nav_menu = '';
  $show_container = false;

  // Create custom walker if one wasn't set.
  if ( empty( $args->walker ) ) {
    $args->walker = new BP_Walker_Nav_Menu;
  }

  // Sanitise values for class and ID.
  $args->container_class = sanitize_html_class( $args->container_class );
  $args->container_id    = sanitize_html_class( $args->container_id );

  // Whether to wrap the ul, and what to wrap it with.
  if ( $args->container ) {

    /**
     * Filters the allowed tags for the wp_nav_menu_container.
     *
     * @since 1.7.0
     *
     * @param array $value Array of allowed tags. Default 'div' and 'nav'.
     */
    $allowed_tags = apply_filters( 'wp_nav_menu_container_allowedtags', array( 'div', 'nav', ) );

    if ( in_array( $args->container, $allowed_tags ) ) {
      $show_container = true;

      $class     = $args->container_class ? ' class="' . esc_attr( $args->container_class ) . '"' : ' class="menu-wonka-container"';
      $id        = $args->container_id    ? ' id="' . esc_attr( $args->container_id ) . '"'       : '';
      $nav_menu .= '<' . $args->container . $id . $class . '>';
    }
  }

  /**
   * Wonka filters the BuddyPress menu objects.
   *
   * @since 1.7.0
   *
   * @param array $value Array of nav menu objects.
   * @param array $args  Array of arguments for the menu.
   */
  $menu_items = apply_filters( 'bp_nav_menu_objects', bp_get_nav_menu_items(), $args );
  
  foreach ( $menu_items as $key => $item ) :
        foreach ( $menu_items as $c => $item_c ) :
          if ( $item_c->parent == $item->css_id ) :
            $item->class = array( 'menu-item', 'menu-item-buddypress-navigation',  'menu-item-has-children' );
            break;
          else:
            $item->class = array( 'menu-item', 'menu-item-buddypress-navigation' );
          endif;
        endforeach;

  endforeach;

  $items      = walk_nav_menu_tree( $menu_items, $args->depth, $args );
  unset( $menu_items );

  // Set the ID that is applied to the ul element which forms the menu.
  if ( ! empty( $args->menu_id ) ) {
    $wrap_id = $args->menu_id;

  } else {
    $wrap_id = 'menu-bp';

    // If a specific ID wasn't requested, and there are multiple menus on the same screen, make sure the autogenerated ID is unique.
    while ( in_array( $wrap_id, $menu_id_slugs ) ) {
      if ( preg_match( '#-(\d+)$#', $wrap_id, $matches ) ) {
        $wrap_id = preg_replace('#-(\d+)$#', '-' . ++$matches[1], $wrap_id );
      } else {
        $wrap_id = $wrap_id . '-1';
      }
    }
  }
  $menu_id_slugs[] = $wrap_id;

  /**
   * Filters the BuddyPress menu items.
   *
   * Allow plugins to hook into the menu to add their own <li>'s
   *
   * @since 1.7.0
   *
   * @param array $items Array of nav menu items.
   * @param array $args  Array of arguments for the menu.
   */
  $items = apply_filters( 'bp_nav_menu_items', $items, $args );

  // Build the output.
  $wrap_class  = $args->menu_class ? $args->menu_class : '';
  $nav_menu   .= sprintf( $args->items_wrap, esc_attr( $wrap_id ), esc_attr( $wrap_class ), $items );
  unset( $items );

  // If we've wrapped the ul, close it.
  if ( ! empty( $show_container ) ) {
    $nav_menu .= '</' . $args->container . '>';
  }

  /**
   * Wonka filters the final BuddyPress menu output.
   *
   * @since 1.7.0
   *
   * @param string $nav_menu Final nav menu output.
   * @param array  $args     Array of arguments for the menu.
   */
  $nav_menu = apply_filters( 'bp_nav_menu', $nav_menu, $args );

  if ( ! empty( $args->echo ) ) {
    echo $nav_menu;
  } else {
    return $nav_menu;
  }
}

/*----------  This is for the buddypress menu  ----------*/
if ( ! function_exists( 'x_buddypress_navbar_menu' ) ) :

    function x_buddypress_navbar_menu( $items, $args ) {

      if ( X_BUDDYPRESS_IS_ACTIVE && x_get_option( 'x_buddypress_header_menu_enable' ) == '1' ) {

        if ( bp_is_active( 'activity' ) ) {
          $logged_out_link = bp_get_activity_directory_permalink();
        } else if ( bp_is_active( 'groups' ) ) {
          $logged_out_link = bp_get_groups_directory_permalink();
        } else {
          $logged_out_link = bp_get_members_directory_permalink();
        }

        // User Icon Link
        $top_level_link = ( ! is_user_logged_in() ) ? '/membership-levels': $logged_out_link;
        $submenu_items  = '';

       
        if ( ! is_user_logged_in() ) {
          if ( bp_get_signup_allowed() ) {
            $submenu_items .= '<li class="menu-item menu-item-buddypress-navigation"><a href="/membership-levels" class="cf"><i class="x-icon-pencil" data-x-icon="&#xf040;" aria-hidden="true"></i> <span>' . x_get_option( 'x_buddypress_register_title' ) . '</span></a></li>';

            // $submenu_items .= '<li class="menu-item menu-item-buddypress-navigation"><a href="' . bp_get_activation_page() . '" class="cf"><i class="x-icon-key" data-x-icon="&#xf084;" aria-hidden="true"></i> <span>' . x_get_option( 'x_buddypress_activate_title' ) . '</span></a></li>';
          }
          $submenu_items .= '<li class="menu-item menu-item-buddypress-navigation"><a href="' . wp_login_url() . '" class="cf"><i class="x-icon-sign-in" data-x-icon="&#xf090;" aria-hidden="true"></i> <span>' . __( 'Log in', '__x__' ) . '</span></a></li>';
        } else {

          if ( bp_is_active( 'activity' ) ) {
            $submenu_items .= '<li class="menu-item menu-item-buddypress-navigation"><a href="' . bp_get_activity_directory_permalink() . '" class="cf"><i class="x-icon-thumbs-up" data-x-icon="&#xf164;" aria-hidden="true"></i> <span>' . x_get_option( 'x_buddypress_activity_title' ) . '</span></a></li>';
          }

          if ( bp_is_active( 'groups' ) ) {
            $submenu_items .= '<li class="menu-item menu-item-buddypress-navigation"><a href="' . bp_get_members_directory_permalink() . '" class="cf"><i class="x-icon-sitemap" data-x-icon="&#xf0e8;" aria-hidden="true"></i> <span>' . __( 'Directory', '__x__' ) . '</span></a></li>';
          }

          if ( is_multisite() && bp_is_active( 'blogs' ) ) {
            $submenu_items .= '<li class="menu-item menu-item-buddypress-navigation"><a href="' . bp_get_blogs_directory_permalink() . '" class="cf"><i class="x-icon-file" data-x-icon="&#xf15b;" aria-hidden="true"></i> <span>' . x_get_option( 'x_buddypress_blogs_title' ) . '</span></a></li>';
          }

          $submenu_items .= '<li class="menu-item menu-item-buddypress-navigation"><a href="/market-place/" class="cf"><i class="x-icon-male" data-x-icon="&#xf07a;" aria-hidden="true"></i> <span>Market Place</span></a></li>';

          $submenu_items .= '<li class="menu-item menu-item-buddypress-navigation"><a href="' . get_post_type_archive_link( 'rsc_videos' ) . '" class="cf"><i class="x-icon-video-camera" data-x-icon="&#xf03d;" aria-hidden="true"></i> <span>' . __( 'Videos', '__x__' ) . '</span></a></li>';

          $submenu_items .= '<li class="menu-item menu-item-buddypress-navigation menu-item-has-children"><a href="/rsc-vroom/" class="cf"><i class="x-icon-users" data-x-icon="" aria-hidden="true"></i> <span>' . __( 'VRoom', '__x__' ) . '</span></a>';
          
          $submenu_items .= '<ul class="sub-menu"><li class="menu-item menu-item-buddypress-navigation"><a href="/create-conference/" class="cf"><i class="x-icon-cog" data-x-icon="&#xf013;" aria-hidden="true"></i> <span>' . __( 'Create Conference', '__x__' ) . '</span></a></li></ul></li>';
          
          $submenu_items .= '<li class="menu-item menu-item-buddypress-navigation menu-item-has-children"><a id="current-user-profile" href="' . bp_loggedin_user_domain() . '" class="cf"><i class="x-icon-cog" data-x-icon="&#xf013;" aria-hidden="true"></i> <span>' . __( 'Profile', '__x__' ) . '</span></a>';

          $submenu_items .= '<ul class="sub-menu"><li class="menu-item menu-item-buddypress-navigation"><a href="' . bp_loggedin_user_domain() . '/profile/" class="cf"><i class="x-icon-cog" data-x-icon="&#xf013;" aria-hidden="true"></i> <span>' . __( 'Edit', '__x__' ) . '</span></a></li></ul></li>';

          $submenu_items .= '<li class="menu-item menu-item-buddypress-navigation"><a href="' . wp_logout_url( home_url() ) . '" class="cf"><i class="x-icon-sign-out" data-x-icon="&#xf08b;" aria-hidden="true"></i> <span>' . __( 'Logout', '__x__' ) . '</span></a></li>';
        }

        if ( $args->theme_location == 'primary' ) {
          if ( ! is_user_logged_in() ) :
            $items .= '<li class="menu-item menu-item-buddypress-navigation menu-item-has-children">'
                      . '<a href="' . $top_level_link . '" class="x-btn-navbar-buddypress cf">'
                        . '<i class="x-icon-user" data-x-icon="&#xf007;" aria-hidden="true"></i> <span class="account-link"> ' . __( ' Become a Rockstar', '__x__' ) . '</span>'
                      . '</a>'
                      . '<ul class="sub-menu">'
                        . $submenu_items
                      . '</ul>'
                    . '</li>';
          else :
            $items .= '<li class="menu-item menu-item-buddypress-navigation menu-item-has-children">'
                      . '<a href="' . $top_level_link . '" class="x-btn-navbar-buddypress cf">'
                        . '<i class="x-icon-user" data-x-icon="&#xf007;" aria-hidden="true"></i> <span class="account-link"> ' . __( ' Members', '__x__' ) . '</span>'
                      . '</a>'
                      . '<ul class="sub-menu">'
                        . $submenu_items
                      . '</ul>'
                    . '</li>';
          endif;
        }
      }

      return $items;

    }
  add_filter( 'wp_nav_menu_items', 'x_buddypress_navbar_menu', 9997, 2 );

endif;

/**
 * Build and populate the Wonka Custom BuddyPress accordion on Appearance > Menus.
 *
 * @since 1.9.0
 *
 * @global $nav_menu_selected_id
 */
function wonka_admin_do_wp_nav_menu_meta_box() {
  global $nav_menu_selected_id;

  $walker = new Wonka_Walker_Nav_Menu_Checklist( false );
  $args   = array( 'walker' => $walker );

  $post_type_name = 'wonka-bpress';

  $tabs = array();

  $tabs['loggedin']['label']  = __( 'Logged-In', 'buddypress' );
  $tabs['loggedin']['pages']  = bp_nav_menu_get_loggedin_pages();

  ?>

  <div id="wonka-bpress-menu" class="posttypediv">
    <h4><?php _e( 'Logged-In', 'buddypress' ) ?></h4>
    <p><?php _e( '<em>Logged-In</em> links are relative to the current user, and are not visible to visitors who are not logged in.', 'buddypress' ) ?></p>

    <div id="tabs-panel-posttype-<?php echo $post_type_name; ?>-loggedin" class="tabs-panel tabs-panel-active">
      <ul id="buddypress-menu-checklist-loggedin" class="categorychecklist form-no-clear">
        <?php echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $tabs['loggedin']['pages'] ), 0, (object) $args );?>
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
        <a href="<?php
        echo esc_url( add_query_arg(
          array(
            $post_type_name . '-tab' => 'all',
            'selectall'              => 1,
          ),
          remove_query_arg( $removed_args )
        ) );
        ?>#buddypress-menu" class="select-all"><?php _e( 'Select All', 'buddypress' ); ?></a>
      </span>
      <span class="add-to-menu">
        <input type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu', 'buddypress' ); ?>" name="add-custom-menu-item" id="submit-buddypress-menu" />
        <span class="spinner"></span>
      </span>
    </p>
  </div><!-- /#wonka-bpress-menu -->

  <?php
}
// Load the Wonka BP metabox in the WP Nav Menu Admin UI.
// add_action( 'load-nav-menus.php', 'wonka_admin_do_wp_nav_menu_meta_box' );