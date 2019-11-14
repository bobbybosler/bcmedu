<?php

// =============================================================================
// VIEWS/INTEGRITY/CONTENT-LINK.PHP
// -----------------------------------------------------------------------------
// Link post output for Integrity.
// Used as External Link posts on bcmedu.org.
// =============================================================================

$link = get_post_meta( get_the_ID(), '_x_link_url',  true );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php if ( is_single() ) : ?>
  <div class="entry-featured">
    <?php x_featured_image(); ?>
  </div>
  <?php else : ?>
  <div class="entry-featured" style="background-image: url('<?php echo get_the_post_thumbnail_url( get_the_ID(), 'entry', NULL ); ?>')">
    <div class="entry-overlay">
    </div>
  </div>
  <?php endif; ?>
  <div class="entry-wrap">
    <header class="entry-header">
      <?php if ( is_single() ) : ?>
      <div class="x-hgroup">
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <span class="entry-title-sub"><a href="<?php echo $link; ?>" title="<?php echo esc_attr( sprintf( __( 'Shared link from post: "%s"', '__x__' ), the_title_attribute( 'echo=0' ) ) ); ?>" target="_blank"><?php echo $link; ?></a></span>
      </div>
      <?php else : ?>
      <?php bcm_category_meta() ?>
      <header class="entry-header">
        <h2 class="entry-title"><a href="<?php echo $link ?>" title="<?php echo esc_attr( sprintf( __( 'External link to: "%s"', '__x__' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php x_the_alternate_title(); ?></a></h2>
      </header>
      <?php endif; ?>
      <?php x_integrity_entry_meta(); ?>
    </header>
    <?php x_get_view( 'global', '_content'); ?>
  </div>
  <?php if ( is_single() ) : ?>
  <?php x_get_view( 'integrity', '_content', 'post-footer' ); ?>
  <?php endif; ?>
</article>
