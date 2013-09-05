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

  ob_start();
  include(dirname(dirname(__FILE__)) . '/templates/shortcode-rotator.php');
  return ob_get_clean();
}
add_shortcode('rotator', 'base_shortcode_rotator');


/*
*  Simple list medias item with pagination
*  Example:
*  [mediatheque collection="photos"]
*  [mediatheque collection="videos"]
*  [mediatheque]
*  [mediatheque per_page="2"]
 */
function base_shortcode_media_list($atts) {
  extract(shortcode_atts(array(
    'collection' => 'mediatheque',
    'per_page'  => -1
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
  */

function base_shortcode_twitter($atts) {
  extract(shortcode_atts(array(
    'id' => '',
    'tweets' =>'10'
  ), $atts));

    $twitter = new TwitterConnect();
    $twitter->render_html( $tweets );
}
add_shortcode('twitter-timeline', 'base_shortcode_twitter');