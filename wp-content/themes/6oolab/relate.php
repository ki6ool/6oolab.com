<?php
global $Cmn, $cat, $post;
$args = array(
		'exclude' => $post->ID,
		'numberposts' => 5,
		'post_status' => 'publish',
);
$ne = get_posts($args);
if ( empty($ne) ) return;
?>

<h3 class="tm-article-subtitle">最新記事</h3>
<ul class="uk-list uk-list-line">
<?php foreach ($ne as $n):?>
<li><a href="/archives/<?php echo $n->ID;?>"><?php echo $n->post_title;;?></a></li>
<?php endforeach;?>
</ul>

<?php
if ( empty($cat) ) return;
$args = array(
		'exclude' => $post->ID,
		'numberposts' => 5,
		'post_status' => 'publish',
		'tax_query' => array(array('terms' => $cat->slug, 'taxonomy' => 'category', 'field' => 'slug'))
);
$re = get_posts($args);
if ( empty($re) ) return;
?>
<h3 class="tm-article-subtitle">関連記事</h3>
<ul class="uk-list uk-list-line">
<?php foreach ($re as $r):?>
<li><a href="/archives/<?php echo $r->ID;?>"><?php echo $r->post_title;;?></a></li>
<?php endforeach;?>
</ul>