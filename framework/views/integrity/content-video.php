<?php

// =============================================================================
// VIEWS/INTEGRITY/CONTENT-VIDEO.PHP
// -----------------------------------------------------------------------------
// Video post output for Integrity.
// Not used on bcmedu.org.
// =============================================================================

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php if ( is_single() ) : ?>
    <?php bcm_category_header(); ?>
    <div class="article-wrap">
    <div class="entry-featured">
    	<?php x_featured_video(); ?>
    </div>
   <?php else : ?>
<div class="entry-wrap image">
    <?php x_get_view( 'integrity', '_content', 'post-header' ); ?>
    <?php x_get_view( 'global', '_content' ); ?>
</div>
  <?php endif; ?>
  <?php if ( is_single() ) : ?>
    <div class="entry-wrap">
    <?php x_get_view( 'integrity', '_content', 'post-header' ); ?>
    <?php x_get_view( 'global', '_content' ); ?>
  </div>
</div>
  <?php else : ?>
  <div class="entry-featured image" style="background-image: url('<?php echo get_the_post_thumbnail_url( get_the_ID(), 'entry', NULL ); ?>')">
    <div class="entry-overlay">
    </div>
  </div>
  <?php endif; ?>
  <?php x_get_view( 'integrity', '_content', 'post-footer' ); ?>
</article>
