<?php
//if ( WP_DEBUG_LOG ) ini_set('error_log', TEMPLATEPATH.'/log.php');
//remove_action('wp_version_check', 'wp_version_check');
//remove_action('admin_init', '_maybe_update_core');
//add_filter('pre_site_transient_update_core', '__return_zero');
//add_filter('site_option__site_transient_update_plugins', '__return_zero');
add_theme_support('post-thumbnails');
/**/

function _v($val) {
	echo "<pre>"; var_dump($val); echo "</pre>";
}

function _h($val) {
	return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
}

function _startsWith($haystack, $needle) {
	if ( is_array($needle) ) {
		return preg_match('/('.implode('|', $needle ).')/', $haystack);
	}
	return strpos($haystack, $needle, 0) === 0;
}

function _endsWith($haystack, $needle) {
	$length = (strlen($haystack) - strlen($needle));
	if ( $length <0 ) return false;
	return strpos($haystack, $needle, $length) !== false;
}

function _matchesIn($haystack, $needle) {
	if ( is_array($needle) ) {
		return preg_match('/'.implode('|', $needle).'/i', $haystack);
	}
	return strpos($haystack, $needle) !== false;
}

function noimage() {
	return "https://placeholdit.imgix.net/~text?txtsize=40&bg=666&txtclr=888&txt=NoImage&w=200&h=200&fm=png";
}

function _shorten($str, $length, $add=false) {
	if (mb_strlen($str) > $length) {
		$str = trim(mb_substr(strip_tags($str), 0, $length, 'UTF-8'));
		return ($add) ? $str.$add : $str;
	}
	return $str;
}

add_action('after_setup_theme', function() {
	add_theme_support('title-tag');
});

add_filter('document_title_parts', function($title) {
	global $page_title;
	$page_title = $title['title'];
	return $title;
});

add_action('init', function() {
	if ( is_admin() ) return;
	add_action('wp_enqueue_scripts', function() {
		wp_enqueue_style('bulma', '//cdnjs.cloudflare.com/ajax/libs/bulma/0.1.2/css/bulma.min.css');
		wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
		wp_enqueue_style('6oolab', '/css/6oolab.css');
		wp_deregister_script('jquery');
		wp_enqueue_script('jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js');
	});
});
