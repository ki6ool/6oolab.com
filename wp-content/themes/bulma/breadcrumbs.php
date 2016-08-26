<?php if ( is_home() ) return;?>
<?php
global $post, $wp_query;
$breadcrumbs[] = ['active' => false, 'text' => 'Home', 'href' => '/'];
if ( is_category() ) {
	$breadcrumbs[] = ['active' => true, 'text' => $wp_query->queried_object->name, 'href' => "/archives/category/{$wp_query->queried_object->slug}"];
} elseif ( is_tag() ) {
	$breadcrumbs[] = ['active' => true, 'text' => $wp_query->queried_object->name, 'href' => "/archives/tag/{$wp_query->queried_object->slug}"];
} elseif ( is_single() ) {
	$category = get_the_category($post->ID);
	$breadcrumbs[] = ['active' => false, 'text' => reset($category)->name, 'href' => "/archives/category/".reset($category)->slug];
	$breadcrumbs[] = ['active' => true, 'text' => $post->post_title, 'href' => get_permalink($post)];
} elseif ( is_search() ) {
	$breadcrumbs[] = ['active' => true, 'text' => $wp_query->query['s'], 'href' => "/?s={$wp_query->query['s']}"];
}
?>
<div class="hero-foot">
	<div class="container">
		<nav class="tabs is-boxed">
			<ul>
<?php foreach ($breadcrumbs as $b) printf('<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="%s"><a itemprop="url" href="%s"><span itemprop="title">%s</span></a></li>', $b[active]?'is-active':'', $b['href'], $b['text']);?>
			</ul>
		</nav>
	</div>
</div>