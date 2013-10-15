<?php

/*
Plugin Name: Inconito Admin Theme
Plugin URI: http://www.inconito.fr
Description: Inconito WordPress Admin Theme - Upload and Activate.
Author: Mathias Gorenflot
Copyright:   © Inconito
Version: 1.0
Author URI: http://www.inconito.fr
*/

function inc_admin_head() {
        echo '<link rel="stylesheet" type="text/css" href="' .plugins_url('wp-admin.css', __FILE__). '">';
}

add_action('admin_head', 'inc_admin_head');


