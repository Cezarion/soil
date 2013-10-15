<?php
/**
 * Custom post types & taxonomies
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
 */

/**
 * Rotator custom post type
 */
function base_register_rotator_post_type() {
  $labels = array(
    'name'               => 'Rotator Items',
    'singular_name'      => 'Rotator Item',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add New Rotator Item',
    'edit_item'          => 'Edit Rotator Item',
    'new_item'           => 'New Rotator Item',
    'view_item'          => 'View Rotator Item',
    'search_items'       => 'Search Rotator Items',
    'not_found'          => 'No rotator items found',
    'not_found_in_trash' => 'No rotator items found in trash',
    'parent_item_colon'  => '',
    'menu_name'          => 'Rotator'
  );

  $args = array(
    'labels'              => $labels,
    'public'              => true,
    'exclude_from_search' => true,
    'publicly_queryable'  => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'query_var'           => true,
    'rewrite'             => array('slug' => 'rotator'),
    'capability_type'     => 'post',
    'has_archive'         => false,
    'hierarchical'        => false,
    'menu_position'       => null,
    'supports'            => array('title', 'thumbnail', 'excerpt')
  );

  register_post_type('base_rotator', $args);
}
//add_action('init', 'base_register_rotator_post_type');

/**
 * Advertise custom post type
 */
function base_register_advertise_post_type() {
  $labels = array(
    'name'               => 'Advertise Items',
    'singular_name'      => 'Advertise Item',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add New Advertise Item',
    'edit_item'          => 'Edit Advertise Item',
    'new_item'           => 'New Advertise Item',
    'view_item'          => 'View Advertise Item',
    'search_items'       => 'Search Advertise Items',
    'not_found'          => 'No advertise items found',
    'not_found_in_trash' => 'No advertise items found in trash',
    'parent_item_colon'  => '',
    'menu_name'          => 'Publicité'
  );

  $args = array(
    'labels'              => $labels,
    'public'              => true,
    'exclude_from_search' => true,
    'publicly_queryable'  => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'query_var'           => true,
    'rewrite'             => array('slug' => 'advertise'),
    'capability_type'     => 'post',
    'has_archive'         => false,
    'hierarchical'        => false,
    'menu_position'       => null,
    'supports'            => array('title', 'custom-fields')
  );

  register_post_type('base_advertise', $args);
}
add_action('init', 'base_register_advertise_post_type');

/**
 * Rotator Location taxonomy
 */
function base_register_location_taxonomy() {
  $labels = array(
    'name'              => 'Locations',
    'singular_name'     => 'Location',
    'search_items'      => 'Search Locations',
    'all_items'         => 'All Locations',
    'parent_item'       => 'Parent Location',
    'parent_item_colon' => 'Parent Location:',
    'edit_item'         => 'Edit Location',
    'update_item'       => 'Update Location',
    'add_new_item'      => 'Add New Location',
    'new_item_name'     => 'New Location Name',
    'menu_name'         => 'Location'
  );

  $args = array(
    'hierarchical' => true,
    'labels'       => $labels,
    'show_ui'      => true,
    'query_var'    => true,
    'rewrite'      => array('slug' => 'rotator-location'),
  );
  register_taxonomy('base_rotator_location', 'base_rotator', $args);
}
//add_action('init', 'base_register_location_taxonomy');

/**
 * Add mediatheque hiérarchical taxonomy for attachments
 */
// register new taxonomy which applies to attachments
function base_register_add_mediatheque_taxonomy() {
    $labels = array(
        'name'              => 'Catégorie',
        'singular_name'     => 'Catégorie',
        'search_items'      => 'Rechercher dans les catégories',
        'all_items'         => 'Toutes les catégories',
        'parent_item'       => 'Catégorie parente',
        'parent_item_colon' => 'Catégorie parente :',
        'edit_item'         => 'Editer la catégorie',
        'update_item'       => 'Mettre à jour la catégorie',
        'add_new_item'      => 'Ajouter une nouvelle catégorie',
        'new_item_name'     => 'Nom de la nouvelle catégorie',
        'menu_name'         => 'Catégories',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'query_var' => 'true',
        'rewrite' => 'true',
        'show_admin_column' => 'true',
    );

    register_taxonomy( 'collection', 'attachment', $args );
    add_post_type_support('attachment', 'collection');
}
add_action( 'init', 'base_register_add_mediatheque_taxonomy' );