<?php

// =============================================================================
// VIEWS/INTEGRITY/TEMPLATE-LAYOUT-PORTFOLIO.PHP
// -----------------------------------------------------------------------------
// Portfolio page output for Integrity.
// =============================================================================

?>

<?php if ( is_user_logged_in() ) : get_header( 'connected' ); else: get_header(); endif; ?>

  <div class="x-container max width offset">
    <div class="<?php x_main_content_class(); ?>" role="main">

      <?php x_get_view( 'global', '_portfolio' ); ?>

    </div>

    <?php get_sidebar(); ?>

  </div>

<?php get_footer(); ?>