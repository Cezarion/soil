<?php
/**
 * Base Query params
 ******************************************* */
$taxonomy = 'collection';

/*
 * Taxonomy query params
 ******************************************* */

$medias_args = array(
    'posts_per_page' => $per_page,
    'post_type'          => 'attachment',
    'post_status'       => 'inherit'
    );

// if a category is specified
if ( !is_null( $category ) )
{
    $tax_args = array
    (
        'taxonomy' => $taxonomy,
        'terms'      => $category,
        'field'        => 'slug'
    );
}

// If set pagination
  if( $per_page >= 0 )
  {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $medias_args['paged'] = $paged;
  }

/*
 * If dropdown
 ******************************************* */
if( $dropdown )
{
    $dropdown_args = array
    (
      'show_option_all' => ( $show_all ) ? __( $taxonomy , 'Roots') :  __('Voir tout', 'Roots'),
      'taxonomy'          => $taxonomy,
      'hide_empty'        => false
    );

  if( !is_null($category) )
  {
    $termObj = get_term_by( 'slug', $category , $taxonomy );

  //If term has child
    if ( $termObj->parent == 0 )
    {
      $dropdown_args['child_of'] = $termObj->term_id;
    }
    else
    {
      $dropdown = FALSE;
    }
  }

  if ( isset( $_GET['cat'] ) && !empty( $_GET['cat'] ) )
  {
    $tax_args = array
    (
        'taxonomy' => $taxonomy ,
        'terms'       =>  $_GET['cat'] ,
        'field'         =>  'id'
    );

    $dropdown_args['selected'] = $_GET['cat'];
  }
}

/*
 * Finally the query
 ******************************************* */
$medias_args['tax_query'] [] = $tax_args;
$medias_query = new WP_Query($medias_args);



/*
 * Responsive Image size
 ******************************************* */
global $_wp_additional_image_sizes; //get registered image size
$get_image_size = 'mediatheque-thumb-'.ioo_get_var('size-suffix');

if ( array_key_exists( 'mediatheque-thumb-'.ioo_get_var('size-suffix') , $_wp_additional_image_sizes) )
  $image_size = $get_image_size;
else
  $image_size = 'mediatheque-thumb-desktop';
?>


<div class="media-collection-container">
  <?php if ( $dropdown ) : ?>
  <form action="<?php the_permalink() ?>" class="form" id="media-dropdown">
    <div class="controls">
        <?php wp_dropdown_categories( $dropdown_args ); ?>
        <input type="submit" class="hide" value="<?php _e('Filter' , 'Roots')?> ">
      </div>
  </form>
  <?php endif; ?>

  <div class="row media-collection media-collection-<?=$category?>">
    <?php while ($medias_query->have_posts()) : $medias_query->the_post(); ?>

      <?php
      $post_terms = get_the_terms( get_the_ID() , "collection" );
      $the_content = '';

      //
      // Set css class
      //
      if( $post_terms && ! is_wp_error( $post_terms ) )
      {
        $media_class = array('media-item');
        $sprite_class = array('sprite');
        foreach ($post_terms as $key => $post_term)
        {
          $media_class[] ="media-item-".$post_term->slug;
          $sprite_class[] ="sprite-media-".$post_term->slug;
          $content_type = $post_term->slug;
        }
      }

      //
      // Légende
      //
      $the_excerpt  =  get_the_excerpt();

      //
      // Images attributes
      //
      $img_thumb = wp_get_attachment_image_src( get_the_ID() , $image_size );
      $img_attr      = array
                              (
                                  'alt'   => trim(strip_tags( get_post_meta($attachment_id, '_wp_attachment_image_alt', true) )), // Use Alt field first
                                  'title' =>  trim(strip_tags( get_the_title() ) )
                              );

      if ( empty($img_attr['alt']) )
        $img_attr['alt'] = trim(strip_tags( get_the_excerpt() )); // If not, Use the Caption
      if ( empty($img_attr['alt']) )
        $img_attr['alt'] = $img_attr['title'] ; // Finally, use the title

      $format = '<img src="%s" class="%s" alt="%s" title="%s"/>';

      switch ( $content_type )
      {
        case 'videos':
            $the_content = get_the_content();
            $the_content = do_shortcode( $the_content );# code...
          break;

        default:
            $img_wide = wp_get_attachment_image_src( get_the_ID() , 'mediatheque-wide' );
            $the_content  =  sprintf( $format , $img_wide[0] , 'media-item-wide' , $img_attr['alt'] , $img_attr['title']);
          break;
      }
      ?>

      <a <?php add_class('media-link span2'); ?> title="<?php the_title_attribute(); ?>">
        <figure <?php add_class($media_class); ?>>
          <?php printf( $format , $img_thumb[0] , 'media-item' , $img_attr['alt'] , $img_attr['title']); ?>
            <script type="text/html" class="media-script">
                    <?php echo $the_content.PHP_EOL; ?>
            <?php if ( !empty( $the_excerpt ) ) :  ?>
                  <div class="media-caption">
                      <?php echo $the_excerpt ?>
                  </div>
            <?php endif; ?>
            </script>
           <span class="overlay"></span>
            <i <?php add_class( $sprite_class ); ?>></i>
        </figure>
      </a>
    <?php endwhile; ?>
  </div>

  <div class="pagination pagination-centered">
    <?php theme_pagination( $medias_query ) ?>
  </div>
</div>

<div id="mediatheque-modal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  </div>
  <div class="modal-body"></div>
</div>
<?php wp_reset_postdata(); ?>