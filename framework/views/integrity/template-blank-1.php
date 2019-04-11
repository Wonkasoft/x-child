<?php

// =============================================================================
// VIEWS/INTEGRITY/TEMPLATE-BLANK-1.PHP (Container | Header, Footer)
// -----------------------------------------------------------------------------
// A blank page for creating unique layouts.
// =============================================================================

?>

<?php if ( is_user_logged_in() ) : get_header( 'connected' ); else: get_header(); endif; ?>

  <div class="x-container">
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