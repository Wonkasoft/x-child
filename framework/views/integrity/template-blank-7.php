<?php

// =============================================================================
// VIEWS/INTEGRITY/TEMPLATE-BLANK-7.PHP (Container | No Header, Footer)
// -----------------------------------------------------------------------------
// A blank page for creating unique layouts.
// =============================================================================

?>

<?php

if ( apply_filters( 'x_legacy_cranium_headers', true ) ) {
  x_get_view( 'global', '_header' );
} else {
  if ( is_user_logged_in() ) : get_header( 'connected' ); else: get_header(); endif;
}

?>

  <div class="x-container max width offset">
    <div class="x-main full" role="main">

      <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <div class="entry-wrap">
            <?php x_get_view( 'global', '_content', 'the-content' ); ?>
          </div>
        </article>

      <?php endwhile; ?>

    </div>
  </div>

<?php get_footer(); ?>