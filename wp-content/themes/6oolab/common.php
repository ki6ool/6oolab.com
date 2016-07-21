<?php
class Common {
	public $site_name;
	public $cats;
	public $tags;

	function __construct() {
		$this->site_name = get_option('blogname');
		$this->cats = get_categories(array('orderby' => 'count', 'order' => 'desc', 'number' => 10));
		$this->tags = get_tags(array('orderby' => 'count', 'order' => 'desc', 'number' => 10));
	}

}


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