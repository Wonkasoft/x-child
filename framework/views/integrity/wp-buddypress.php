<?php

// =============================================================================
// VIEWS/INTEGRITY/WP-BUDDYPRESS.PHP
// -----------------------------------------------------------------------------
// BuddyPress output for Integrity.
// =============================================================================

?>

<?php if ( is_user_logged_in() ) : get_header( 'connected' ); else: get_header(); endif; ?>

  <div class="x-container">
    <div class="<?php x_main_content_class(); ?>" role="main">

      <?php while ( have_posts() ) : the_post(); ?>
        <?php x_get_view( 'global', '_content', 'buddypress' ); ?>
      <?php endwhile; ?>

    </div>

    <?php get_sidebar(); ?>

  </div>

<?php get_footer(); ?>