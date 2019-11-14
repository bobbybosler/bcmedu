<?php

// =============================================================================
// VIEWS/INTEGRITY/CONTENT-IMAGE.PHP
// -----------------------------------------------------------------------------
// Image post output for Integrity.
// Used as single image posts on bcmedu.org.
// =============================================================================

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php if ( is_single() ) : ?>
  <div class="entry-featured">
    <?php x_featured_image(); ?>
  </div>
  <?php else : ?>
  <a href="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'full', NULL ); ?>" class="entry-featured single-image entry-image-wrap image-lightbox-<?php echo get_the_ID(); ?>" data-caption="<?php the_excerpt(); ?>">
	    <div class="entry-image" style="background-image: url('<?php echo get_the_post_thumbnail_url( get_the_ID(), 'entry', NULL ); ?>')">
	    <div class="image-overlay">
	    </div>
	    </div>
	</a>
	<?php echo do_shortcode('[lightbox selector=".image-lightbox-' . get_the_ID() . '"]'); ?>
  <?php endif; ?>
    <?php if ( is_single() ) : ?>
  <div class="entry-wrap">
      <header class="entry-header">
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php x_integrity_entry_meta(); ?>
      </header>
    <?php x_get_view( 'global', '_content'); ?>
    <?php x_get_view( 'integrity', '_content', 'post-footer' ); ?>
    </div>
    <?php else : ?>
      <div class="entry-wrap single-image">
          <?php bcm_category_meta() ?>
      <header class="entry-header">
        <h2 class="entry-title"><a href="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'full', NULL ); ?>" class="entry-featured single-image entry-image-wrap image-lightbox-2-<?php echo get_the_ID(); ?>" data-caption="<?php the_excerpt(); ?>"><?php the_title() ?></a></h2>
	<?php echo do_shortcode('[lightbox selector=".image-lightbox-2-' . get_the_ID() . '"]'); ?>
      </header>
    <?php x_get_view( 'global', '_content'); ?>
  </div>
    <?php endif; ?>
</article>
