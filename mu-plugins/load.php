<?php
/*
Plugin Name:  Must Use Plugins Loader
Plugin URI:   http://benword.com/
Description:  Options Framework, CMB, CF Post Formats, and site-specific functionality (custom post types, taxonomies, meta boxes, shortcodes)
Version:      1.0
Author:       Ben Word
Author URI:   http://benword.com/
*/

//Theme
require WPMU_PLUGIN_DIR . '/inconito-admin-theme/inconito-admin-theme.php';

//require WPMU_PLUGIN_DIR . '/mu-root-relative-urls/sb_root_relative_urls.php';

//require WPMU_PLUGIN_DIR . '/options-framework-plugin/options-framework.php';
require WPMU_PLUGIN_DIR . '/wp-post-formats/cf-post-formats.php';

//Attachements tools
require WPMU_PLUGIN_DIR . '/force-regenerate-thumbnails/force-regenerate-thumbnails.php';
require WPMU_PLUGIN_DIR . '/enable-media-replace/enable-media-replace.php';

//Backup and db tools
require WPMU_PLUGIN_DIR . '/wp-migrate-db/wp-migrate-db.php';

// Site specific custom post types, taxonomies, meta boxes and shortcodes
require WPMU_PLUGIN_DIR . '/base/base.php';

// Metabox

/* CMB */
/*
function load_cmb() {
  if (!is_admin()) {
    return;
  }

  require WPMU_PLUGIN_DIR . '/cmb/init.php';
}
add_action('init', 'load_cmb');
*/

/* FieldManager */
function load_fieldmanager() {
	require WPMU_PLUGIN_DIR . '/fieldmanager/fieldmanager.php';
}
add_action('init', 'load_fieldmanager', 0);
//require WPMU_PLUGIN_DIR . '/fieldmanager/fieldmanager.php';