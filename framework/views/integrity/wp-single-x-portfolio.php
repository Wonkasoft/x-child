<?php

// =============================================================================
// VIEWS/INTEGRITY/WP-SINGLE-X-PORTFOLIO.PHP
// -----------------------------------------------------------------------------
// Single portfolio post output for Integrity.
// =============================================================================

?>

<?php if ( is_user_logged_in() ) : get_header( 'connected' ); else: get_header(); endif; ?>
  
  <div class="x-container max width offset">
    <div class="x-main full" role="main">

      <?php while ( have_posts() ) : the_post(); ?>
        <?php x_get_view( 'integrity', 'content', 'portfolio' ); ?>
        <?php x_get_view( 'global', '_comments-template' ); ?>
      <?php endwhile; ?>

    </div>
  </div>

<?php get_footer(); ?>