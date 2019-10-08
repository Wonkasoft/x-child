<?php

// =============================================================================
// HEADER-CONNECTED.PHP
// -----------------------------------------------------------------------------
// The site header.
// =============================================================================

$x_root_atts = x_atts( apply_filters( 'x_root_atts', array( 'id' => 'x-root', 'class' => 'x-root' ) ) );
$x_site_atts = x_atts( apply_filters( 'x_site_atts', array( 'id' => 'x-site', 'class' => 'x-site site' ) ) );

$atts = x_atts( array(
  'class' => 'x-masthead',
  'role'  => 'banner'
) );

?>

<!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

<head>
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div <?php echo $x_root_atts; ?>>

		<?php do_action( 'x_before_site_begin' ); ?>

		<div <?php echo $x_site_atts; ?>>
			<?php do_action( 'x_after_site_begin' ); ?>

			<?php do_action( 'x_before_masthead_begin' ); ?>
			<header <?php echo $atts; ?>>
				<div class="x-logobar">
					<div class="x-logobar-inner">
						<div class="x-container max width">
							<?php x_get_view( 'global', '_brand' ); ?>
						</div>
					</div>
				</div>

				<div class="x-navbar-wrap">
					<div class="<?php x_navbar_class(); ?>">
						<div class="x-navbar-inner">
							<div class="x-container max width">
								<?php wp_nav_menu( array(
									'theme_location' => 'profile_menu',
									'container'      => false,
									'menu_class'     => 'x-nav',
									'link_before'    => '<span>',
									'link_after'     => '</span>'
								) ); ?>
							</div>
						</div>
					</div>
				</div>
			</header>
			<?php do_action( 'x_after_masthead_end' ); ?>