<?php if ( is_home() ) return;?>
<div class="hero-body">
	<div class="container">
	<?php if ( is_single() || is_page() ):?>
		<h1 class="title"><?php echo $post->post_title;?></h1>
		<h2 class="subtitle"><?php echo get_the_time('Y-m-d H:i');?></h2>
	<?php elseif ( is_archive() ):?>
		<h1 class="title"><?php echo ucfirst($wp_query->queried_object->name);?></h1>
		<h2 class="subtitle"><?php echo str_replace('post_', '', $wp_query->queried_object->taxonomy);?></h2>
	<?php elseif ( is_search() ):?>
		<h1 class="title">Search</h1>
		<h2 class="subtitle"><?php echo $wp_query->query['s'];?></h2>
	<?php endif;?>
	</div>
</div><!-- /.hero-body -->