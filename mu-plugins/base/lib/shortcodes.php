<?php
/**
 * Custom shortcodes
 */

/**
 * [rotator] shortcode
 *
 * Output posts from the base_rotator custom post type
 * Use location="" attribute to pull in posts from a specific location
 * from the base_rotator_location taxonomy
 *
 * Example:
 * [rotator location="home"]
 */
function base_shortcode_rotator($atts) {
  extract(shortcode_atts(array(
    'location' => ''
  ), $atts));

  wp_enqueue_script('jquery-ui-core');

  ob_start();
  include(dirname(dirname(__FILE__)) . '/templates/shortcode-rotator.php');
  return ob_get_clean();
}
add_shortcode('rotator', 'base_shortcode_rotator');


/**
 * [rotator] Bootstrap grid shorcode
 *
 * create html div grid from bootstrap 2.3.2
 *
 * Example:
 * [rotator location="home"]
 */

/* ROW
 * ****************************************   */

 // Row [row class='addiitionnal class']content[/row]
function bootstrap_row_shortcode( $atts, $content = null ) {
    extract( shortcode_atts( array(
      'class' => '' ,
      ), $atts ) );

    return '<div class="row '.$class.'">'. do_shortcode($content) . '</div>';
}
add_shortcode('row','bootstrap_row_shortcode', 7 );

// Row Fluid [row-fluid class='addiitionnal class']content[/row-fluid]
function bootstrap_rowfluid_shortcode( $atts, $content = null ) {
    extract( shortcode_atts( array(
      'class' => '' ,
      ), $atts ) );

    return '<div class="row-fluid '.$class.'">'. do_shortcode($content) . '</div>';
}
add_shortcode('row-fluid','bootstrap_rowfluid_shortcode' , 7);

// Span [span col="7" class='addiitionnal class']content[/span]
function bootstrap_span_shortcode( $atts, $content = null )
{
    extract( shortcode_atts( array(
      'col' => '' ,
      'class' =>''
      ), $atts ) );

    ob_start(); ?>
    <div class="span<?php echo $col; ?>"><?php echo do_shortcode($content) ?></div>
    <?php return ob_get_clean();
}
add_shortcode('span','bootstrap_span_shortcode' , 7);

// Media object [media-block url="http://path/to/my/image" class='addiitionnal class']content[/media-block]
function bootstrap_media_shortcode( $atts, $content = null )
{
    extract( shortcode_atts( array(
      'image' => '' ,
      'class' => '' ,
      ), $atts ) );

    $class = ( !empty($class) ) ? 'media '.$class : 'media';
    $html =
<<<HTML
    <div class="{$class}">
        <span class="pull-left">
            <img class="media-object" src="{$image}" />
        </span>
        <div class="media-body">
            {$content}
        </div>
    </div>
HTML;

    return $html;
}
add_shortcode('media-block','bootstrap_media_shortcode' , 7);

// Media sprite [media-sprite icon="media-photo" class='additionnal class']content[/media-block]
function bootstrap_media_sprite_shortcode( $atts, $content = null )
{
    extract( shortcode_atts( array(
      'icon' => '' ,
      'class' => '' ,
      ), $atts ) );

    $class = ( !empty($class) ) ? 'media '.$class : 'media';
    $html =
<<<HTML
    <div class="{$class}">
        <span class="pull-left">
            <i class="sprite sprite-{$icon}"></i>
        </span>
        <div class="media-body">
            {$content}
        </div>
    </div>
HTML;

    return $html;
}
add_shortcode('media-sprite','bootstrap_media_sprite_shortcode' , 7);



/*
*  Simple list medias item with pagination
*  Example:
*  [mediatheque category="photos"]
*  [mediatheque category="videos" dropdown="show"]
*  [mediatheque]
*  [mediatheque per_page="2"]
 */
function base_shortcode_media_list($atts) {
  extract(shortcode_atts(array(
    'category'    => NULL,
    'per_page'   => -1,
    'dropdown'  => FALSE
  ), $atts));

  ob_start();
  include(dirname(dirname(__FILE__)) . '/templates/shortcode-media-list.php');
  return ob_get_clean();
}
add_shortcode('mediatheque', 'base_shortcode_media_list');

/*
  * Create a twitter timeline for account
  * Define account params in wp-content/theme/MY_THEME/lib/config.php
  *
  * @params
  *         'id' => NULL,
  *         'tweets' =>NULL,
  *         'width' =>NULL,
  *         'height' =>NULL,
  *         'show_account' => FALSE,
  *         'show_actions' => FALSE,
  *         'show_avatar' => FALSE
  */

function base_shortcode_twitter($atts)
{
    $twitter = new TwitterConnect();
    ob_start();
    $twitter->render_html( $atts );
    return ob_get_clean();
}
add_shortcode('twitter-timeline', 'base_shortcode_twitter');