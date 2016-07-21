<?php
if ( WP_DEBUG_LOG ) ini_set('error_log', TEMPLATEPATH.'/log.php');
date_default_timezone_set('Asia/Tokyo');
require_once 'common.php';
global $Cmn;
$Cmn = new Common();

//remove_action('wp_version_check', 'wp_version_check');
//remove_action('admin_init', '_maybe_update_core');
//add_filter('pre_site_transient_update_core', '__return_zero');
//add_filter('site_option__site_transient_update_plugins', '__return_zero');
add_theme_support('post-thumbnails');
/**/

add_filter('wp_title', function($title) {
	$title .= get_bloginfo('name');
	$site_description = get_bloginfo('description', 'display');
	if ( $site_description && ( is_home() ) ) {
		$title = "{$title} | {$site_description}";
	}
	return $title;
});

/**
 * ページネイト
 */
function get_pagination() {
	global $wp_query;
	$src = "";
	$big = 99999999;
	$current = max(1, get_query_var('paged'));
	$page_format = paginate_links( array(
		'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big)) ),
		'format' => '?paged=%#%',
		'current' => $current,
		'total' => $wp_query->max_num_pages,
		'type'  => 'array',
		'prev_text'    => __('<i class="uk-icon-angle-double-left"></i>'),
		'next_text'    => __('<i class="uk-icon-angle-double-right"></i>'),
	) );
	if( is_array($page_format) ) {
		$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
		$src .= "<ul class=\"uk-pagination\">\n";
		foreach ($page_format as $page) {
    		$src .= "<li>{$page}</li>\n";
		}
		$src .= "</ul>";
	}
	wp_reset_query();
	return $src;
}

add_action('init', function() {
	if ( !is_admin() ) {
		wp_deregister_script('jquery');
		wp_enqueue_script('jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js');
	}
});

/**
 * 本文内のURLに<a>タグを付与する
 */
/*
add_filter('the_content', function($content) {
	if ( is_singular() ) {
		$host = $_SERVER["HTTP_HOST"];
		//外部サイト
		$content = preg_replace("/(https?:\/\/)(?!{$_SERVER["HTTP_HOST"]})([-_.!~*'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)/", "<a href=\"\\1\\2\" target=\"_blank\" rel=\"nofollow\">\\1\\2</a>", $content);
		//自サイト
		$content = preg_replace("/(https?:\/\/{$_SERVER["HTTP_HOST"]})([-_.!~*'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)/", "<a href=\"\\2\">\\1\\2</a>", $content);
	}
	return $content;
});
*/