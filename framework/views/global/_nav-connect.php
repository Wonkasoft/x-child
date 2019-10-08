<?php

// =============================================================================
// VIEWS/GLOBAL/_NAV-PRIMARY.PHP
// -----------------------------------------------------------------------------
// Outputs the primary nav.
// =============================================================================
	
if( function_exists( 'ubermenu' ) && $config_id = ubermenu_get_menu_instance_by_theme_location( 'primary' ) ):
	ubermenu( $config_id, array( 'theme_location' => 'primary') ); 
 else: ?>
 
<a href="#" class="x-btn-navbar collapsed" data-toggle="collapse" data-target=".x-nav-wrap.mobile">
  <i class="x-icon-bars" data-x-icon="&#xf0c9;"></i>
  <span class="visually-hidden"><?php _e( 'Navigation', '__x__' ); ?></span>
</a>

<nav class="x-nav-wrap desktop" role="navigation">
  <?php 
  	wonka_bp_nav_menu( array( 
  		'menu'           => 'Profile Navigation Menu',
        'theme_location' => 'primary',
        'container'      => false,
        'menu_id'		 => 'menu-bp-desktop',
        'menu_class'     => 'x-nav x-nav-scrollspy',
        'link_before'    => '<span>',
        'link_after'     => '</span>'
  	) );
   ?>
</nav>

<div class="x-nav-wrap mobile collapse">
  <?php 
		wonka_bp_nav_menu( array( 
			'menu'           => 'Profile Navigation Menu',
	        'theme_location' => 'primary',
	        'container'      => false,
	        'menu_id'		 => 'menu-bp-mobile',
	        'menu_class'     => 'x-nav x-nav-scrollspy',
	        'link_before'    => '<span>',
	        'link_after'     => '</span>' 
		) );
   ?>
</div>

<?php endif; ?>