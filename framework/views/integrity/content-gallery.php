<?php

// =============================================================================
// VIEWS/INTEGRITY/CONTENT-GALLERY.PHP
// -----------------------------------------------------------------------------
// Gallery post output for Integrity.
// =============================================================================

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php if ( is_single() ) : ?>
  <?php /*bcm_category_header();*/ ?>
  <div class="category-header wrap">
    <div class="category-header-content">
      <?php x_get_view( 'integrity', '_content', 'post-header' ); ?>
      <?php the_date('', '<div class="title-subhead">Published ', '</div>'); ?>
    </div>
  </div>
<div class="article-wrap">
  <?php x_get_view( 'global', '_content' ); ?>
  <div class="category-header-footer"><i class="fas fa-chevron-down"></i></div>
  <div class="entry-featured">
    <?php
      if ( function_exists( 'envira_gallery' ) ) {
        envira_gallery( get_field('envira_gallery_id') );
      }
    ?>
  </div>

  <?php else : ?>

  <div class="entry-wrap image">
    <?php x_get_view( 'integrity', '_content', 'post-header' ); ?>
    <?php x_get_view( 'global', '_content' ); ?>
  </div>
  <?php x_get_view( 'integrity', '_content', 'post-footer' ); ?>

  <?php endif; ?>


  <?php if ( is_single() ) : ?>

  <?php /*<div class="entry-wrap">
    <?php x_get_view( 'integrity', '_content', 'post-header' ); ?>
    <?php x_get_view( 'global', '_content' ); ?>
  </div> */?>
  <?php x_get_view( 'integrity', '_content', 'post-footer' ); ?>
</div>
  <?php else : ?>

  <div class="entry-featured image" style="background-image: url('<?php echo get_the_post_thumbnail_url( get_the_ID(), 'entry', NULL ); ?>')">
    <?php
    /* x_featured_image(); */
    $post_thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'full', NULL );
    $text_to_be_wrapped_in_shortcode = /*'<img src="' . $post_thumbnail_url . '">'*/ '<div class="entry-overlay"></div>';

    if ( (get_field('envira_gallery_id')) != NULL ) {

      echo do_shortcode(
        '[envira-link class="entry-thumb" id="' . get_field('envira_gallery_id') . '"]'
        . $text_to_be_wrapped_in_shortcode
        . '[/envira-link]'
      );
    } else {
      echo $text_to_be_wrapped_in_shortcode;
    }

    ?>
  </div>


  <?php endif; ?>

</article>
