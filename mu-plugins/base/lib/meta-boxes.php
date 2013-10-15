<?php
/**
 * Custom meta boxes with the CMB plugin
 *
 * @link https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 * @link https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress/wiki/Basic-Usage
 * @link https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress/wiki/Field-Types
 * @link https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress/wiki/Display-Options
 */

// cf require WPMU_PLUGIN_DIR . '/cmb/example-functions.php';

/**
 * Custom meta boxes with the FieldManager  plugin
 *
 * @link https://github.com/alleyinteractive/wordpress-fieldmanager
 * @link http://fieldmanager.org/
 * @link http://api.fieldmanager.org/
 */

// using Fieldmanager for a slideshow - any number of slides, with any number of related links
function timer_data_fonction()
{
  for ( $i = 1 ; $i < 20 ; $i++ )
  {
    $datas[$i] = array( 'name' => $i.' secondes' , 'value' => $i );
  }
  return $datas;
}

function get_post_id()
{
  if( isset( $_GET['post'] ) )
      return '[adv id="'.$_GET['post'].'"]';
  else
      return 'Waiting to save advertise';
}


add_action( 'init', function()
{
  $fm_prefix= '_fm_';

  $fmParams = new Fieldmanager_Group
  (
    array
    (
      'name' => $fm_prefix.'advertise_slideshow_parameters',
      'limit' => 1,
      'label' => null,
      'label_macro' => array( 'Advertise: %s', 'title' ),
      'collapsed' => FALSE,
      'children' => array
        (
          'adv_shortcode' => new Fieldmanager_Textfield
          (
              array
              (
                  'label' => 'Shortcode',
                  'default_value' => get_post_id(),
                  'inline_label' => true,
                  'field_class' => 'disabled input-paste',
                  'attributes' => array( 'disabled' => 'disabled')
              )
          ),
          'adv_theme' => new Fieldmanager_Radios
          (
              array
              (
                  'label' => 'Advertise Format',
                  'name' => 'adv_theme',
                  'inline_label' => true,
                  'skip_save' => true,
                  'field_class' => 'inline',
                  'default_value' => '468x240',
                  'data' => array
                        (
                                  array( 'name' => 'Wide - 468x240' , 'value' => '468x240'),
                                  array( 'name' => 'Small - 468x90' , 'value' => '468x90' )
                        )
              )
          ),
          'adv_timer' => new Fieldmanager_Select
          (
            array
            (
              'label' => 'Transition duration',
              'inline_label' => true,
              'name' => 'adv_timer',
              'default_value' => '7',
              'data' => timer_data_fonction()
            )
          ),
        ),
    )
  );
  //printr($fmParams);
  $fmParams->add_meta_box( __('Slideshow Params' , 'base' ), array( 'base_advertise' ) , 'normal' , 'high' );

  $fmDatas = new Fieldmanager_Group
  (
  array
    (
      'name' => $fm_prefix.'advertise',
      'limit' => 0,
      'starting_count' => 1,
      'extra_elements' => 0,
      'label' => 'New Advertise',
      'label_macro' => array( 'Advertise: %s', 'adv_title' ),
      'add_more_label' => 'Add another advertise',
      'collapsed' => FALSE,
      'collapsible' => true,
      'sortable' =>True,
      'children' => array
        (
          'adv_title' => new Fieldmanager_Textfield
          (
              array
              (
                'label' => 'Advertise Title' ,
                'inline_label' => true ,
                'validation_rules' => 'required'
              )
          ),
          'adv_link' => new Fieldmanager_Textfield
          (
              array
              (
                  'label' => 'Advertise Link' ,
                  'inline_label' => true,
                  'validation_rules' => array('required' => 1 , 'url' => 1),
              )
          ),
          'adv_image' => new Fieldmanager_Media
          (
            array
            (
              'label' => 'Advertise Image' ,
              'description' => 'Upload an image that\'s the same dimension as the theme chosen<br/><code>468px x 240px</code> or <code>468px x 90px</code>. <br/>Otherwise, it will be cropped',
              'inline_label' => true ,
              'validation_rules' => 'required'
            )
          ),
        ),
    )
  );

  $fmDatas->add_meta_box( __('Slideshow Items' , 'base' ), array( 'base_advertise' )  , 'normal' , 'high' );
} );