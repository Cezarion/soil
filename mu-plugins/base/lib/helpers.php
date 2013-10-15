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
 * Get post meta with identified prefix
 * @param  $post_id post_id
 * @param  $prefix meta_key prefix
 * @return a data object with all the post_meta identified by the prefix
 */

function get_post_meta_with_prefix($post_id, $prefix=NULL){
    global $wpdb;
        $query_prefix  = ($prefix !== NULL) ?  " AND `meta_key` LIKE '".$prefix."%'" : '';

    $data   =   array();
    $wpdb->query("
        SELECT `meta_key`, `meta_value`
        FROM $wpdb->postmeta
        WHERE `post_id` = $post_id
        $query_prefix
    ");

    foreach($wpdb->last_result as $k => $v)
		$data[$v->meta_key] =   maybe_unserialize($v->meta_value);

    $data = (object) $data;

    return $data;
}

/**
 *
 */
if( !function_exists('post_exists_id'))
{
	function post_exists_id($post_id)
	{
		global $wpdb;

		$post_id = wp_unslash( sanitize_post_field( 'ID', $post_id, 0, 'db' ) );

		$query = "SELECT ID FROM $wpdb->posts WHERE 1=1";
		$query .= ' AND ID = %s';
		$args[] = $post_id;

		if ( !empty ( $args ) )
			return (int) $wpdb->get_var( $wpdb->prepare($query, $args) );

		return FALSE;
	}
}


/**
 * Get desired path info from wp_upload_dir()
 * @param  key (possible value : path , url , subdir , basedir , baseurl )
 * @return  value
 */
if( !function_exists( 'get_upload_dir' ) )
{
	function get_upload_dir( $key = 'baseurl' , $time = null )
	{
		$path = wp_upload_dir( $time );
		return $path[$key];
	}
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

/**
 * Check an utube url and force it to display in html5
 * @param  $utube url
 * @return  $utube url vith html5 param
 */

function uTube_video_iframe( $url )
{
	return preg_replace_callback('#(?:https?://\S+)|(?:www.\S+)|(?:\S+\.\S+)#', function($arr)
	{
	    $url = parse_url($arr[0]);
	    // youtube
	    if( in_array($url['host'], array('www.youtube.com', 'youtube.com')) )
	    {
	    	  unset($url['scheme']);
	        if ( !isset($url['query'] ) )
	        {
	            $url['query'] = 'wmode=opaque&html5=1';
	        }
	        else
	        {
	            if( strstr($url['query'] , 'v=' )  == 0 )
	            {
	                $url['query'] = substr( $url['query'] , 2 );
	            }

	            if( strpos( strtolower ( $url['query'] ) , 'html5=true') == 0 )
	            {
	                $url['query'] = $url['query'].'/?html5=1';
	            }
	        }
	        if( $url['path'] == '/watch' )
	        {
	            $url['path'] = 'embed';
	        }

	      return  vsprintf('//%s/%s/%s' , $url );
	    }
	    else
	    {
	        return false;
	    }

	}, $url );
}

/**
 * Create page breadcrumb
 * @param  none
 * @return  utml formated breadcrumb
 */

function the_breadcrumb( $wrapper_class = 'breadcrumb' , $separator = "/" )
{
	//sprintf params
	$archive		= 	_x('Archive for' , 'Base');
	$li 				= 	'		<li class="'.$wrapper_class.'-item">%s</li>'.PHP_EOL;
	$li_arhive 		= 	'		<li class="'.$wrapper_class.'-item">%s : %s</li>'.PHP_EOL.'%s';
	$li_link 		= 	'		<li class="'.$wrapper_class.'-item"><a href="%1$s" title="%2s">%2$s</a></li>%3$s';
	$li_sep  		= 	PHP_EOL.'		<li class="'.$wrapper_class.'-separator">'.$separator.'</li>'.PHP_EOL;

	global $post;

	echo '<ul class="inline '.$wrapper_class.'">'.PHP_EOL;
	if ( !is_home() )
	{
		printf( $li_link , get_option('home') , _x('Home' , 'Roots') , $li_sep);

		if (is_category() || is_single())
		{
			echo '		<li>';
			the_category('</li>'.$li_sep.'		<li> ');
			if (is_single())
			{
				echo '</li>'.$li_sep;
				printf( $li , the_title() );
			}
		}
		elseif (is_page())
		{
			if($post->post_parent)
			{
				$anc = get_post_ancestors( $post->ID );

				foreach ( $anc as $ancestor )
				{
					$output = sprintf( $li_link , get_permalink($ancestor) , get_the_title($ancestor) , $li_sep );
				}
				echo $output;
				echo '<span class="'.$wrapper_class.'-current" title="'.get_the_title().'"> '.get_the_title().'</span>';
			}
			else
			{
				echo '<span class="'.$wrapper_class.'-current" title="'.get_the_title().'"> '.get_the_title().'</span>';
			}
		}
	}
	elseif (is_tag())
	{
		single_tag_title();
	}
	elseif (is_day())
	{
		$time = the_time('F jS, Y');
		printf( $li_arhive , $txt , $time );
	}
	elseif (is_month())
	{
		$the_time('F, Y');
		printf( $li_arhive , $txt , $time );
	}
	elseif (is_year())
	{
		$the_time('Y');
		printf( $li_arhive , $txt , $time );
	}
	elseif (is_author())
	{
		printf( $li , _x( 'Author Archive' , 'Base' ) );
	}
	elseif (isset($_GET['paged']) && !empty($_GET['paged']))
	{
		printf( $li , _x( 'Blog Archives' , 'Base' ) );
	}
	elseif (is_search())
	{
		printf( $li , _x( 'Search Results' , 'Base' ) );
	}
	echo '</ul>';
}