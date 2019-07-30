<?php

// =============================================================================
// VIEWS/INTEGRITY/CONTENT-GALLERY.PHP
// -----------------------------------------------------------------------------
// Gallery post output for Integrity.
// =============================================================================

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php if ( is_single() ) : ?>
  <?php bcm_category_header(); ?>
<div class="article-wrap">
  <div class="entry-featured">
    <?php
      if ( function_exists( 'envira_gallery' ) ) {
        envira_gallery( get_field('envira_gallery_id') );
      }
      /*x_featured_gallery();*/ ?>
  </div>

  <?php else : ?>

  <div class="entry-wrap image">
    <?php x_get_view( 'integrity', '_content', 'post-header' ); ?>
    <?php x_get_view( 'global', '_content' ); ?>
  </div>
  <?php x_get_view( 'integrity', '_content', 'post-footer' ); ?>

  <?php endif; ?>


  <?php if ( is_single() ) : ?>

  <div class="entry-wrap">
    <?php x_get_view( 'integrity', '_content', 'post-header' ); ?>
    <?php x_get_view( 'global', '_content' ); ?>
  </div>
  <?php x_get_view( 'integrity', '_content', 'post-footer' ); ?>
</div>
  <?php else : ?>

  <div class="entry-featured image">
    <?php
    if ( function_exists( 'envira_gallery' ) ) {
      envira_gallery( get_field('envira_gallery_id') );
    }
    /*x_featured_gallery();*/ ?>
  </div>


  <?php endif; ?>

</article>
