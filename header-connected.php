<?php

// =============================================================================
// HEADER-CONNECTED.PHP
// -----------------------------------------------------------------------------
// The site header.
// =============================================================================

$x_root_atts = x_atts( apply_filters( 'x_root_atts', array( 'id' => 'x-root', 'class' => 'x-root' ) ) );
$x_site_atts = x_atts( apply_filters( 'x_site_atts', array( 'id' => 'x-site', 'class' => 'x-site site' ) ) );

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
			<header class="<?php x_masthead_class(); ?>" role="banner">
				<?php x_get_view( 'global', '_topbar' ); ?>
				<?php x_get_view( 'global', '_connectbar' ); ?>
				
			</header>
			<?php do_action( 'x_after_masthead_end' ); ?>