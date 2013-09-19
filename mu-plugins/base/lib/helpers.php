<?php
/**
 * Some usefull helper for coding
 *
 */

/**
 * Indent print_r;
 * @param  $array
 * @return 	html indented print_r()
 */
function printr( $array ){
	echo '<pre class="debug pre">';
		print_r( $array );
	echo '</pre>';
}

/**
 * Create pagination for custom query
 * @param  object $wpQuery the wp_query
 * @return  wp html formated pagination
 */

if( !function_exists( 'add_class' ) )
{
	function add_class( $class = '' )
	{
		$classes = array();

		if ( !empty($class) )
		{
			if ( !is_array( $class ) )
	                  $class = preg_split('#\s+#', $class);

	            $classes = array_merge($classes, $class);
	        }
	        $classes = array_map('esc_attr', $classes);

	    	echo 'class="' . join( ' ', $class ).'"';
	}
}

/**
 * Create pagination for custom query
 * @param  none
 * @return  current page
 */

function current_page_url() {
	$pageURL = 'http';
	if( isset($_SERVER["HTTPS"]) ) {
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

/**
 * Create pagination for custom query
 * @param  object $wpQuery the wp_query
 * @return  wp html formated pagination
 */

if( !function_exists( 'theme_pagination' ) ) {

    function theme_pagination( $wpQuery ) {

	global $wp_rewrite, $paged;
	$wpQuery->query_vars['paged'] > 1 ? $current = $wpQuery->query_vars['paged'] : $current = 1;

	$pagination = array(
		'base' => @add_query_arg( 'page' , '%#%'  , get_permalink() ),
		'format' => '',
		'total' => $wpQuery->max_num_pages,
		'current' => $current,
	        'show_all' => false,
	        'end_size'     => 1,
	        'mid_size'     => 2,
		'type' => 'list',
		'next_text' => '»',
		'prev_text' => '«'
	);

	//Dirty but works
	//@Todo : find a better way
	if( !empty($_SERVER['QUERY_STRING']) )
		$uri =  explode ( $_SERVER['QUERY_STRING']  , get_pagenum_link( 1 ) );
	else
		$uri[0] = get_pagenum_link( 1 );

	$query_string = '?'.filter_var ( $_SERVER['QUERY_STRING']  , FILTER_SANITIZE_ENCODED , FILTER_FLAG_ENCODE_HIGH );

	if( $wp_rewrite->using_permalinks() )
		$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', $uri[0] ) ) . 'page/%#%/', 'paged' ).$query_string;

	if( !empty($wpQuery->query_vars['s']) )
		$_pagination['add_args'] = array( 's' => str_replace( ' ' , '+', get_query_var( 's' ) ) );

	//printr( $wpQuery->query_vars['tax_query'] );


	echo str_replace('page/1/','', paginate_links( $pagination ) );
    }
}