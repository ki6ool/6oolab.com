<?php
global $post;
$category = get_the_category($post->ID);
if ( empty($category) ) return;
$query = new WP_Query([
		'exclude' => $post->ID,
		'showposts' => 5,
		'post_status' => 'publish',
		'tax_query' => [['terms' => reset($category)->slug, 'taxonomy' => 'category', 'field' => 'slug']]
]);
if ( empty($query->posts) ) return;
?>

<hr>

<section class="section">
	<div class="container">
		<div class="content">
<ul>
<?php foreach ($query->posts as $qp) printf('<li><a href="%s">%s</a></li>', get_permalink($qp), $qp->post_title);?>
</ul>
		</div>
	</div>
</section>