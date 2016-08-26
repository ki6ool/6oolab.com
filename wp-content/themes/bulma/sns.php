<?php
global $post;
$link = get_permalink($post->ID);
$title = $post->post_title;
?>
<nav class="level">
	<p class="level-item has-text-centered">
		<a class="button is-primary" href="https://twitter.com/intent/tweet?text=<?php echo $title.esc_url($link);?>">
			<span class="icon"><i class="fa fa-twitter"></i></span>
			<span>Twitter</span>
		</a>
	</p>
	<p class="level-item has-text-centered">
		<a class="button is-info" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url($link);?>">
			<span class="icon"><i class="fa fa-facebook"></i></span>
			<span>Facebook</span>
		</a>
	</p>
	<p class="level-item has-text-centered">
		<a class="button is-danger" href="http://getpocket.com/edit?url=<?php echo esc_url($link);?>">
			<span class="icon"><i class="fa fa-get-pocket"></i></span>
			<span>Pocket</span>
		</a>
	</p>
	<p class="level-item has-text-centered">
		<a class="button is-success" href="http://line.me/R/msg/text/?<?php echo $title.esc_url($link);?>">
			<span>Line</span>
		</a>
	</p>
	<p class="level-item has-text-centered">
		<a class="button is-info" href="http://b.hatena.ne.jp/entry/<?php echo esc_url($link);?>">
			<span>Hatena</span>
		</a>
	</p>
</nav>