<?php

// =============================================================================
// VIEWS/INTEGRITY/CONTENT.PHP
// -----------------------------------------------------------------------------
// Standard post output for Integrity.
// =============================================================================

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php if ( is_single() ) : ?>
  <?php x_get_view( 'global', '_content' ); ?>
  <?php x_get_view( 'integrity', '_content', 'post-footer' );?>
  <?php else : ?>
  <div class="entry-featured" style="background-image: url('<?php echo get_the_post_thumbnail_url( get_the_ID(), 'entry', NULL ); ?>')">
    <div class="entry-overlay">
    </div>
  </div>
  <div class="entry-wrap">
    <?php x_get_view( 'integrity', '_content', 'post-header' ); ?>
    <?php x_get_view( 'global', '_content' ); ?>
    <?php x_get_view( 'integrity', '_content', 'post-footer' ); ?>
  </div>
<?php endif; ?>
</article>
