<?php

// =============================================================================
// FUNCTIONS.PHP
// -----------------------------------------------------------------------------
// Overwrite or add your own custom functions to Pro in this file.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Enqueue Parent Stylesheet
//   02. Additional Functions
// =============================================================================

// Enqueue Parent Stylesheet
// =============================================================================

add_filter( 'x_enqueue_parent_stylesheet', '__return_true' );

add_action( 'get_footer', 'child_style_in_footer' );

function child_style_in_footer() {
    wp_enqueue_style( 'child-style', get_stylesheet_uri() );
}


// Additional Functions
// =============================================================================


//
// Add "Artist" custom attribute after shop title
// =============================================================================

add_action('woocommerce_shop_loop_item_title', 'bcm_insertAfterShopProductTitle', 15);

function bcm_insertAfterShopProductTitle()
{
    global $product;

    $musicartist = '<span class="pa-artist">' . $product->get_attribute('pa_artist') . '</span>';
    if (empty($musicartist))
        return;
    echo __($musicartist, 'woocommerce');
}

//
// Add custom attribute to subhead after single product page title
// =============================================================================

add_action('woocommerce_single_product_summary', 'bcm_insertAfterTemplateSingleTitle', 6);

function bcm_insertAfterTemplateSingleTitle()
{
    global $product;

    $artist = $product->get_attribute('pa_artist');
    $voicing = $product->get_attribute('voicing');
    $format = $product->get_attribute('format');
    $arrby = $product->get_attribute('arranged-by');
    $musicby = $product->get_attribute('music-by');
    $subpre = '<div class="pa-subhead">';
    $subsep = ', ';
    $subend = '</div>';

    if (!empty($artist)) {
	    echo __($subpre . $artist . $subend, 'woocommerce');
    } elseif (!empty($voicing)) {
		echo __($subpre . $voicing . $subsep);
		if (!empty($arrby)) {
			echo __('Arr. by ' . $arrby);
		} elseif (!empty($musicby)) {
			echo __($musicby);
		}
		echo __($subend, 'woocommerce');
	} elseif (!empty($format)) {
		echo __($subpre . $format . $subsep);
		if (!empty($arrby)) {
			echo __('Arr. by ' . $arrby);
		} elseif (!empty($musicby)) {
			echo __($musicby);
		}
		echo __($subend, 'woocommerce');
    } else {
    	return;
    }
}


//
// Show all parents, regardless of post status.
// =============================================================================

/**
 * @param   array  $args  Original get_pages() $args.
 *
 * @return  array  $args  Args set to also include posts with private status.
 */
function my_slug_show_all_parents( $args ) {
	$args['post_status'] = array( 'publish', 'private' );
	return $args;
}
add_filter( 'page_attributes_dropdown_pages_args', 'my_slug_show_all_parents' );
add_filter( 'quick_edit_dropdown_pages_args', 'my_slug_show_all_parents' );


//
// Add User Role in body classes (for displaying according to user roles)
// =============================================================================

function get_user_role() {
    global $current_user;
    $user_roles = $current_user->roles;
    $user_roles_list = implode(" ", $user_roles);
    return $user_roles_list;
}

add_filter('body_class','my_class_names');
function my_class_names($classes) {
    $classes[] = get_user_role();
    return $classes;
}

//
// Ignore Sticky Functionality in Main Blog
// =============================================================================

function ignore_sticky_posts_in_blog( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) {
		$query->set( 'ignore_sticky_posts', 'true' );
	}
}
add_action( 'pre_get_posts', 'ignore_sticky_posts_in_blog' );



//
// Modify Cherry Team Member Query to reorder team members
// =============================================================================

add_action( 'pre_get_posts', 'modify_team_query' );
function modify_team_query( $query ) {

	// Check if on frontend
        // and query post_type is team
        // and Page is Faculty page (ID:152)
	if( ! is_admin() && $query->query_vars['post_type'] == 'team' && is_page(152) ) {

		$query->set('meta_key', 'last_name');
		$query->set('orderby','meta_value title');
 		$query->set('order', 'ASC');

	// Check if on frontend
        // and query post_type is team
        // and Page is Admin page (ID:117)
	} elseif( ! is_admin() && $query->query_vars['post_type'] == 'team' && is_page(117) ) {

		$query->set('meta_key', 'last_name');
		$query->set('orderby','menu_order meta_value title');
 		$query->set('order', 'ASC');
   }
}



// Displaying Excerpt in Recent Posts
// =============================================================================



function x_shortcode_recent_posts_v2( $atts ) {
  extract( shortcode_atts( array(
    'id'           => '',
    'class'        => '',
    'style'        => '',
    'type'         => 'post',
    'count'        => '',
    'category'     => '',
    'offset'       => '',
    'orientation'  => '',
    'show_excerpt' => 'true',
    'no_sticky'    => '',
    'no_image'     => '',
    'fade'         => ''
  ), $atts, 'x_recent_posts' ) );

  $allowed_post_types = apply_filters( 'cs_recent_posts_post_types', array( 'post' => 'post' ) );
  $type = ( isset( $allowed_post_types[$type] ) ) ? $allowed_post_types[$type] : 'post';

  $id            = ( $id           != ''     ) ? 'id="' . esc_attr( $id ) . '"' : '';
  $class         = ( $class        != ''     ) ? 'x-recent-posts cf ' . esc_attr( $class ) : 'x-recent-posts cf';
  $style         = ( $style        != ''     ) ? 'style="' . $style . '"' : '';
  $count         = ( $count        != ''     ) ? $count : 3;
  $category      = ( $category     != ''     ) ? $category : '';
  $category_type = ( $type         == 'post' ) ? 'category_name' : 'portfolio-category';
  $offset        = ( $offset       != ''     ) ? $offset : 0;
  $orientation   = ( $orientation  != ''     ) ? ' ' . $orientation : ' horizontal';
  $show_excerpt  = ( $show_excerpt == 'true' ) ? true : false;
  $no_sticky     = ( $no_sticky    == 'true' );
  $no_image      = ( $no_image     == 'true' ) ? $no_image : '';
  $fade          = ( $fade         == 'true' ) ? $fade : 'false';

  $js_params = array(
    'fade' => ( $fade == 'true' )
  );

  $data = cs_generate_data_attributes( 'recent_posts', $js_params );

  $output = "<div {$id} class=\"{$class}{$orientation}\" {$style} {$data} data-fade=\"{$fade}\" >";

    $q = new WP_Query( array(
      'orderby'             => 'date',
      'post_type'           => "{$type}",
      'posts_per_page'      => "{$count}",
      'offset'              => "{$offset}",
      "{$category_type}"    => "{$category}",
      'ignore_sticky_posts' => $no_sticky
    ) );

    if ( $q->have_posts() ) : while ( $q->have_posts() ) : $q->the_post();

      if ( $no_image == 'true' ) {
        $image_output       = '';
        $image_output_class = 'no-image';
      } else {
        $image              = wp_get_attachment_image_src( get_post_thumbnail_id(), 'entry-cropped' );
        $bg_image           = ( $image[0] != '' ) ? ' style="background-image: url(' . $image[0] . ');"' : '';
        $image_output       = '<div class="x-recent-posts-img"' . $bg_image . '></div>';
        $image_output_class = 'with-image';
      }

// Start variable for excerpt early.
      $excerpt = ( $show_excerpt ) ? '<div class="x-recent-posts-excerpt"><p>' . preg_replace('/<a.*?more-link.*?<\/a>/', '', get_the_excerpt() ) . '</p></div>' : '';

// If post format is "Link", then return external link, otherwise get the post url.

      if ( has_post_format( 'link' )) {
        $link = get_post_meta( get_the_ID(), '_x_link_url', true) . '" target="_blank';
      } elseif ( has_post_format( 'image' )) {
        $link = get_the_post_thumbnail_url( get_the_ID(), 'full', NULL );
        $image_output_class .= ' image-lightbox-' . get_the_ID();
        $excerpt .= do_shortcode('[lightbox selector=".image-lightbox-' . get_the_ID() . '"]');
      } else {
        $link = get_permalink( get_the_ID() );
      }

	  $cat = get_the_category();
	  $category = $cat[0]->name;



// Added $link in place of get_permalink( get_the_ID() )

      $output .= '<a class="x-recent-post' . $count . ' ' . $image_output_class . '" href="' . $link . '">'
                 . '<article id="post-' . get_the_ID() . '" class="' . implode( ' ', get_post_class() ) . '">'
                   . '<div class="entry-wrap">'
                     . $image_output
                     . '<div class="x-recent-posts-content">'
                       . '<span style="margin-bottom: 10px;" class="x-recent-posts-date">' . get_the_date() . ' ' . $category . ' </span>'
                       . '<h3 class="h-recent-posts">' . get_the_title() . '</h3>'
                        . $excerpt
                     . '</div>'
                   . '</div>'
                 . '</article>'
               . '</a>';

    endwhile; endif; wp_reset_postdata();

  $output .= '</div>';

  return $output;

}

add_filter('wp_head', 'custom_recent_posts');

function custom_recent_posts() {
  remove_shortcode( 'x_recent_posts' );
  remove_shortcode( 'recent_posts' );
  add_shortcode( 'x_recent_posts', 'x_shortcode_recent_posts_v2' );
  add_shortcode( 'recent_posts', 'x_shortcode_recent_posts_v2' );
}


//
// Change Wordpress Post Format Names
//

function rename_post_formats($translation, $text, $context, $domain) {
    $names = array(
        'Audio'  => 'Event',
        'Standard' => 'News Item',
        'Image' => 'Single Image',
        'Gallery' => 'Photo Gallery',
        'Link' => 'External Link',
        'Quote' => 'Custom'
    );
    if ($context == 'Post format') {
        $translation = str_replace(array_keys($names), array_values($names), $text);
    }
    return $translation;
}
add_filter('gettext_with_context', 'rename_post_formats', 10, 4);

//
// Remove Certain Post Formats
//

function bweb_remove_post_formats() {
    add_theme_support( 'post-formats', array( 'audio', 'gallery', 'image', 'video', 'link', 'quote' ) );
}
add_action( 'after_setup_theme', 'bweb_remove_post_formats', 11 );


// Custom Edits for Sermon Manager Plugin
// for Archive Entry Display

add_filter( 'wpfc_sermon_excerpt_v2', function(){

  global $post;
	if ( empty( $args ) ) {
		$args = array(
			'image_size' => 'post-thumbnail',
		);
	}
	ob_start();
	?>
	<?php if ( ! ( \SermonManager::getOption( 'theme_compatibility' ) || ( defined( 'WPFC_SM_SHORTCODE' ) && WPFC_SM_SHORTCODE === true ) ) ) : ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php endif; ?>
	<div class="wpfc-sermon-inner">
		<div class="wpfc-sermon-img-url" style="display:none;"><?php echo get_the_post_thumbnail_url( null, 'full' ); ?></div>
		<div class="wpfc-sermon-main <?php echo get_sermon_image_url() ? '' : 'no-image'; ?>">
			<div class="wpfc-sermon-header">
				<div class="wpfc-sermon-header-main">
					<?php if ( has_term( '', 'wpfc_sermon_series', $post->ID ) ) : ?>
						<div class="wpfc-sermon-meta-item wpfc-sermon-meta-series">
							<?php the_terms( $post->ID, 'wpfc_sermon_series' ); ?>
						</div>
					<?php endif; ?>
					<?php if ( ! ( \SermonManager::getOption( 'theme_compatibility' ) && ! ( defined( 'WPFC_SM_SHORTCODE' ) && WPFC_SM_SHORTCODE === true ) ) ) : ?>
						<h3 class="wpfc-sermon-title">
							<a class="wpfc-sermon-title-text" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
					<?php endif; ?>
					<div class="wpfc-sermon-meta-item wpfc-sermon-meta">
						<span class="wpfc-date-meta-text">Preached on <?php sm_the_date(); ?></span>
						<?php if ( has_term( '', 'wpfc_preacher', $post->ID ) ) : ?>
							<span class="wpfc-preacher-meta-text"> | <?php the_terms( $post->ID, 'wpfc_preacher' ); ?></span>
						<?php endif; ?>
						<?php if ( get_post_meta( $post->ID, 'bible_passage', true ) ) : ?>
							<span class="wpfc-passage-meta-text"> | <?php wpfc_sermon_meta( 'bible_passage' ); ?></span>
						<?php endif; ?>
					</div>
				</div>
				<?php if ( \SermonManager::getOption( 'archive_meta' ) ) : ?>
					<div class="wpfc-sermon-header-aside">
						<?php if ( get_wpfc_sermon_meta( 'sermon_audio' ) ) : ?>
							<a class="wpfc-sermon-att-audio dashicons dashicons-media-audio"
									href="<?php echo get_wpfc_sermon_meta( 'sermon_audio' ); ?>"
									download="<?php echo basename( get_wpfc_sermon_meta( 'sermon_audio' ) ); ?>"
									title="Audio"></a>
						<?php endif; ?>
						<?php if ( get_wpfc_sermon_meta( 'sermon_notes' ) ) : ?>
							<a class="wpfc-sermon-att-notes dashicons dashicons-media-document"
									href="<?php echo get_wpfc_sermon_meta( 'sermon_notes' ); ?>"
									download="<?php echo basename( get_wpfc_sermon_meta( 'sermon_notes' ) ); ?>"
									title="Notes"></a>
						<?php endif; ?>
						<?php if ( get_wpfc_sermon_meta( 'sermon_bulletin' ) ) : ?>
							<a class="wpfc-sermon-att-bulletin dashicons dashicons-media-text"
									href="<?php echo get_wpfc_sermon_meta( 'sermon_bulletin' ); ?>"
									download="<?php echo basename( get_wpfc_sermon_meta( 'sermon_bulletin' ) ); ?>"
									title="Bulletin"></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
			<?php $sermon_description = get_post_meta( $post->ID, 'sermon_description', true ); ?>
			<div class="wpfc-sermon-description"><?php echo wp_trim_words( $sermon_description, 30 ); ?></div>
			<?php if ( \SermonManager::getOption( 'archive_player' ) && get_wpfc_sermon_meta( 'sermon_audio' ) ) : ?>
				<div class="wpfc-sermon-audio">
					<?php echo wpfc_render_audio( get_wpfc_sermon_meta( 'sermon_audio' ), wpfc_get_media_url_seconds( get_wpfc_sermon_meta( 'sermon_audio' ) ) ); ?>
					<button class="wpfc-sermon-single-audio-download" href="<?php echo get_wpfc_sermon_meta('sermon_audio') ?>" download="<?php echo basename( get_wpfc_sermon_meta( 'sermon_audio' ) ) ?>">
								<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
									<path d="M0 0h24v24H0z" fill="none"/>
									<path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/>
								</svg>
							</button>
				</div>
			<?php endif; ?>

<!--			<div class="wpfc-sermon-footer">
				<?php if ( has_term( '', 'wpfc_preacher', $post->ID ) ) : ?>
					<div class="wpfc-sermon-meta-item wpfc-sermon-meta-preacher">
						<?php
						echo apply_filters( 'sermon-images-list-the-terms', '',
							array(
								'taxonomy'     => 'wpfc_preacher',
								'after'        => '',
								'after_image'  => '',
								'before'       => '',
								'before_image' => '',
							)
						);
						?>
						<span class="wpfc-sermon-meta-prefix">
							<?php echo ( \SermonManager::getOption( 'preacher_label', '' ) ) ?: __( 'Preacher', 'sermon-manager-for-wordpress' ); ?>
							:</span>
						<span class="wpfc-sermon-meta-text"><?php the_terms( $post->ID, 'wpfc_preacher' ); ?></span>
					</div>
				<?php endif; ?>
				<?php if ( get_post_meta( $post->ID, 'bible_passage', true ) ) : ?>
					<div class="wpfc-sermon-meta-item wpfc-sermon-meta-passage">
						<span class="wpfc-sermon-meta-prefix">
							<?php echo __( 'Passage', 'sermon-manager-for-wordpress' ); ?>:</span>
						<span class="wpfc-sermon-meta-text"><?php wpfc_sermon_meta( 'bible_passage' ); ?></span>
					</div>
				<?php endif; ?>
				<?php if ( has_term( '', 'wpfc_service_type', $post->ID ) ) : ?>
					<div class="wpfc-sermon-meta-item wpfc-sermon-meta-service">
						<span class="wpfc-sermon-meta-prefix">
							<?php echo __( 'Service Type', 'sermon-manager-for-wordpress' ); ?>:</span>
						<span class="wpfc-sermon-meta-text"><?php the_terms( $post->ID, 'wpfc_service_type' ); ?></span>
					</div>
				<?php endif; ?>
			</div>-->
		</div>
	</div>

	<?php if ( ! ( \SermonManager::getOption( 'theme_compatibility' ) || ( defined( 'WPFC_SM_SHORTCODE' ) && WPFC_SM_SHORTCODE === true ) ) ) : ?>
		</article>
	<?php endif; ?>

	<?php
	$output = ob_get_clean();
	/**
	 * Allows you to modify the sermon HTML on archive pages.
	 *
	 * @param string  $output The HTML that will be outputted.
	 * @param WP_Post $post   The sermon.
	 *
	 * @since 2.12.0
	 */
//	$output = apply_filters( 'wpfc_sermon_excerpt_v2', $output, $post );
//	if ( ! $return ) {
//		echo $output;
//	}
	return $output;

} );

// Custom Edits for Sermon Manager Plugin
// for Single Page Display

add_filter( 'wpfc_sermon_single_v2', function(){

if ( $post === null ) {
		global $post;
	}
	ob_start();
	?>

    <article id="post-<?php the_ID(); ?>"  <?php post_class(); ?> <?php
            echo "style=\"background-image:url('" . get_the_post_thumbnail_url( null, 'full' ) . "');\"";
            ?>>
        <div class="wpfc-sermon-single-inner">
<!--            <?php if ( get_sermon_image_url() && ! \SermonManager::getOption( 'disable_image_single' ) ) : ?>
			<div class="wpfc-sermon-single-image">
				<?php render_sermon_image('medium'); ?>
			</div>
	    <?php endif; ?>-->
            <div class="wpfc-sermon-single-main">
                <div class="wpfc-sermon-single-header">
                    <div class="wpfc-sermon-single-meta-item wpfc-sermon-single-meta-date">
						<?php sm_the_date() ?>
                    </div>
                    <h2 class="wpfc-sermon-single-title"><?php the_title() ?></h2>
                    <div class="wpfc-sermon-single-meta">
						<?php if ( has_term( '', 'wpfc_preacher', $post->ID ) ) : ?>
                            <div class="wpfc-sermon-single-meta-item wpfc-sermon-single-meta-preacher">
                                <span class="wpfc-sermon-single-meta-prefix"><?php echo __( 'Preacher:', 'sermon-manager-for-wordpress' ) ?></span>
                                <span class="wpfc-sermon-single-meta-text"><?php the_terms( $post->ID, 'wpfc_preacher' ) ?></span>
                            </div>
						<?php endif; ?>
						<?php if ( has_term( '', 'wpfc_sermon_series', $post->ID ) ) : ?>
                            <div class="wpfc-sermon-single-meta-item wpfc-sermon-single-meta-series">
                                <span class="wpfc-sermon-single-meta-prefix"><?php echo __( 'Series:', 'sermon-manager-for-wordpress' ) ?></span>
                                <span class="wpfc-sermon-single-meta-text"><?php the_terms( $post->ID, 'wpfc_sermon_series' ) ?></span>
                            </div>
						<?php endif; ?>
						<?php if ( get_post_meta( $post->ID, 'bible_passage', true ) ) : ?>
                            <div class="wpfc-sermon-single-meta-item wpfc-sermon-single-meta-passage">
                                <span class="wpfc-sermon-single-meta-prefix"><?php echo __( 'Passage:', 'sermon-manager-for-wordpress' ) ?></span>
                                <span class="wpfc-sermon-single-meta-text"><?php wpfc_sermon_meta( 'bible_passage' ) ?></span>
                            </div>
						<?php endif; ?>
						<?php if ( has_term( '', 'wpfc_service_type', $post->ID ) ) : ?>
                            <div class="wpfc-sermon-single-meta-item wpfc-sermon-single-meta-service">
                                <span class="wpfc-sermon-single-meta-prefix"><?php echo __( 'Service Type:', 'sermon-manager-for-wordpress' ) ?></span>
                                <span class="wpfc-sermon-single-meta-text"><?php the_terms( $post->ID, 'wpfc_service_type' ) ?></span>
                            </div>
						<?php endif; ?>
                    </div>
                </div>

                <div class="wpfc-sermon-single-media">
					<?php if ( get_wpfc_sermon_meta( 'sermon_video_link' ) ) : ?>
                        <div class="wpfc-sermon-single-video wpfc-sermon-single-video-link">
							<?php echo wpfc_render_video( get_wpfc_sermon_meta( 'sermon_video_link' ) ); ?>
                        </div>
					<?php endif; ?>
					<?php if ( get_wpfc_sermon_meta( 'sermon_video' ) ) : ?>
                        <div class="wpfc-sermon-single-video wpfc-sermon-single-video-embed">
							<?php echo do_shortcode( get_wpfc_sermon_meta( 'sermon_video' ) ); ?>
                        </div>
					<?php endif; ?>

					<?php if ( get_wpfc_sermon_meta( 'sermon_audio' ) ) : ?>
                        <div class="wpfc-sermon-single-audio">
							<?php echo wpfc_render_audio( get_wpfc_sermon_meta( 'sermon_audio' ) ); ?>
							<a class="wpfc-sermon-single-audio-download" href="<?php echo get_wpfc_sermon_meta('sermon_audio') ?>" download="<?php echo basename( get_wpfc_sermon_meta( 'sermon_audio' ) ) ?>">
								<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
									<path d="M0 0h24v24H0z" fill="none"/>
									<path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/>
								</svg>
							</a>
                        </div>
					<?php endif; ?>
                </div>

                <div class="wpfc-sermon-single-description"><?php wpfc_sermon_description() ?></div>
				<?php if ( get_wpfc_sermon_meta( 'sermon_audio' ) || get_wpfc_sermon_meta( 'sermon_notes' ) || get_wpfc_sermon_meta( 'sermon_bulletin' ) ) : ?>
<!--                    <div class="wpfc-sermon-single-attachments"><?php echo wpfc_sermon_attachments(); ?></div>
				<?php endif; ?>
				<?php if ( has_term( '', 'wpfc_sermon_topics', $post->ID ) ) : ?>
                    <div class="wpfc-sermon-single-topics">
                        <span class="wpfc-sermon-single-topics-prefix"><?php echo __( 'Topics:', 'sermon-manager-for-wordpress' ) ?></span>
                        <span class="wpfc-sermon-single-topics-text"><?php the_terms( $post->ID, 'wpfc_sermon_topics' ) ?></span>
                    </div>
				<?php endif; ?>-->
            </div>
        </div>
    </article>

	<?php
	$output = ob_get_clean();
	/**
	 * Allows you to modify the sermon HTML on single sermon pages
	 *
	 * @param string  $output The HTML that will be outputted
	 * @param WP_Post $post   The sermon
	 *
	 * @since 2.12.0
	 */

//	if ( ! $return ) {
//		echo $output;
//	}
	return $output;

} );

// Custom Edits for Sermon Manager Plugin
// Return latest series image function

function render_series_image( $size ) {
	// $size = any defined image size in WordPress.
	apply_filters( 'sermon-images-list-the-terms', '', array( 'taxonomy' => 'wpfc_sermon_series' ) );
	// Get series image.
	print apply_filters( 'sermon-images-list-the-terms', '', array(
		'image_size'   => $size,
		'taxonomy'     => 'wpfc_sermon_series',
		'after'        => '',
		'after_image'  => '',
		'before'       => '',
		'before_image' => '',
	) );
}

// Add latest series image shortcode
function return_latest_series_image() {
	// Setting up the Query - see http://codex.wordpress.org/Class_Reference/WP_Query
$latest_sermon = new WP_Query(array(
	'post_type' => 'wpfc_sermon',
	'posts_per_page' => 1,
	'post_status' => 'publish',
	// Do you want to limit it to a specific service type? Use the service type slug to do it:
	// More info here: http://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters
	'order' => 'DESC',
	'meta_key' => 'sermon_date',
        'meta_value' => date("m/d/Y"),
        'meta_compare' => '>=',
        'orderby' => 'meta_value',
	// The last three parameters will optimize your query
    'no_found_rows' => true,
    'update_post_term_cache' => false,
    'update_post_meta_cache' => false
	));
if ($latest_sermon->have_posts()) :
?>
	<?php  while ($latest_sermon->have_posts()) : $latest_sermon->the_post(); ?>
	<?php global $post; ?>

	<div class="latest-series-image">
		<?php echo render_series_image("large"); ?>
	</div>
	<?php endwhile; ?>
	<?php // reset the $post variable like below: ?>
	<?php wp_reset_postdata(); ?>
<?php endif;
}
add_shortcode( 'latest_series_image', 'return_latest_series_image' );



//
// Add Category Meta for Post Archives
//

function bcm_category_meta() {

 if ( get_post_type() == 'x-portfolio' ) {
      if ( has_term( '', 'portfolio-category', NULL ) ) {
        $categories        = get_the_terms( get_the_ID(), 'portfolio-category' );
        $separator         = ', ';
        $categories_output = '';
        foreach ( $categories as $category ) {
          $categories_output .= '<a href="'
                              . get_term_link( $category->slug, 'portfolio-category' )
                              . '" title="'
                              . esc_attr( sprintf( __( "View all posts in: &ldquo;%s&rdquo;", '__x__' ), $category->name ) )
                              . '"><i class="x-icon-bookmark" data-x-icon="&#xf02e;"></i> '
                              . $category->name
                              . '</a>'
                              . $separator;
        }

        $categories_list = sprintf( '<span>%s</span>',
          trim( $categories_output, $separator )
        );
      } else {
        $categories_list = '';
      }
    } else {
      $categories        = get_the_category();
      $separator         = '';
      $categories_output = '';
      foreach ( $categories as $category ) {
        $categories_output .= '<a href="'
                            . get_category_link( $category->term_id )
                            . '" title="'
                            . esc_attr( sprintf( __( "View all posts in: &ldquo;%s&rdquo;", '__x__' ), $category->name ) )
                            . '"><i class="x-icon-bookmark" data-x-icon="&#xf02e;"></i> '
                            . $category->name
                            . '</a>'
                            . $separator;
      }

      $categories_list = sprintf( '<span>%s</span>',
        trim( $categories_output, $separator )
      );
    }

print( '<div class="category-meta">' . $categories_list . '</div>');

}

//
// Add Category Header for Single Post Pages
//

function bcm_category_header() {

 if ( get_post_type() == 'x-portfolio' ) {
      if ( has_term( '', 'portfolio-category', NULL ) ) {
        $categories        = get_the_terms( get_the_ID(), 'portfolio-category' );
        $separator         = ', ';
        $categories_output = '';
        foreach ( $categories as $category ) {
          $categories_output .= $category->name;
        }

        $categories_list = sprintf( '<span>%s</span>',
          trim( $categories_output, $separator )
        );
      } else {
        $categories_list = '';
      }
    } else {
      $categories        = get_the_category();
      $separator         = ', ';
      $categories_output = '';
      foreach ( $categories as $category ) {
        $categories_output .= '<span class="bcm-cat-span">'
                           . $category->name
                           . '</span>';
      }

      $categories_list = sprintf( '<h1 class="bcm-cat-head">%s</h1>',
        trim( $categories_output, $separator )
      );
    }

print( '<div class="category-header wrap"><div class="category-header-content">' . $categories_list . '</div></div>');

}



//
// Enqueue Script for multiselecttocheckboxes
//

function add_multiselect_script() {
    wp_register_script( 'multiSelectToCheckboxes', get_stylesheet_directory_uri() . '/js/multiSelectToCheckboxes.js', array( 'jquery' ) );
    wp_enqueue_script( 'multiSelectToCheckboxes' );
}

add_action( 'wp_enqueue_scripts', 'add_multiselect_script' );

//
// Enqueue Script for jquery-observe
//

function add_jqueryobserve_script() {
    wp_register_script( 'jqueryobserve', get_stylesheet_directory_uri() . '/js/jquery-observe.js', array( 'jquery' ) );
    wp_enqueue_script( 'jqueryobserve' );
}

add_action( 'wp_enqueue_scripts', 'add_jqueryobserve_script' );

//
// Add New Menu Locations
//

function register_my_menu() {
  register_nav_menu('mobile-menu',__( 'Mobile Menu' ));
}
add_action( 'init', 'register_my_menu' );
