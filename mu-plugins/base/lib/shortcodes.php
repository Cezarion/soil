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
 * [adv] shortcode
 *
 * Output carousel of advertise
 * Use id=""
 * from the base_advertise custom post type
 *
 * Example:
 * [adv id="adv-id"]
 */

class Base_Shortcode_Adv {

  static function init() {
    add_shortcode('adv', array(__CLASS__, 'handle_shortcode'));
    add_action('after_setup_theme', array(__CLASS__, 'register_adv_size'));
  }

  static function handle_shortcode($atts) {

    extract(shortcode_atts(array(
      'id' => ''
      ), $atts));

    ob_start();
    include(dirname(dirname(__FILE__)) . '/templates/shortcode-advertise-rotator.php');
    return ob_get_clean();
  }

  static function register_adv_size() {
    add_image_size('adv-468x240', 468 , 240 , true );
    add_image_size('adv-468x90', 468, 90 , true);
  }
}

Base_Shortcode_Adv::init();

/**
 * Bootstrap grid shorcode
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

    $content = preg_replace('/<br class="nc".\/>/', '', $content);
    $result  = '<div class="row '.esc_attr($class).'">';
    $result  .= do_shortcode($content);
    $result  .= '</div>';


    ob_start();
    echo    force_balance_tags( $result );
    return ob_get_clean();
}
add_shortcode('row','bootstrap_row_shortcode', 7 );

// Row Fluid [row-fluid class='addiitionnal class']content[/row-fluid]
function bootstrap_rowfluid_shortcode( $atts, $content = null ) {
    extract( shortcode_atts( array(
      'class' => '' ,
      ), $atts ) );

    $content = preg_replace('/<br class="nc".\/>/', '', $content);
    $result  = '<div class="row-fluid '.esc_attr($class).'">';
    $result  .= do_shortcode($content);
    $result  .= '</div>';

    ob_start();
    echo    force_balance_tags( $result );
    return ob_get_clean();
}
add_shortcode('row-fluid','bootstrap_rowfluid_shortcode' , 7);

// Span [span col="7" class='addiitionnal class']content[/span]
function bootstrap_span_shortcode( $atts, $content = null )
{
    extract( shortcode_atts( array(
      'col' => '' ,
      'class' =>''
      ), $atts ) );

    $content = preg_replace('/<br class="nc".\/>/', '', $content);
    $result  = '<div class="span'.esc_attr($col).' '.esc_attr($class).'">';
    $result  .= do_shortcode($content);
    $result  .= '</div>';

    ob_start();
    echo    force_balance_tags( $result );
    return ob_get_clean();
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
function base_shortcode_youtube($atts) {
        extract(shortcode_atts(array(
                "url" => 'http://',
                "width" => '475',
                "height" => '350',
                "allowfullscreen" => 'true'
        ), $atts));

        $allowfullscreen = ( $allowfullscreen ) ? ' allowfullscreen' : '';

        if( function_exists('uTube_video_iframe'))
          $iframe_url = uTube_video_iframe( $url );
          ob_start();
          echo '<iframe width="'.$width.'" height="'.$height.'" src="'.$iframe_url.'" frameborder="0"'.$allowfullscreen.' class="media-iframe"></iframe>';
          return ob_get_clean();
}
add_shortcode("youtube", "base_shortcode_youtube");
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