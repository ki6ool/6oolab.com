<?php get_header();?>

<section class="section">
	<div class="container">
		<div class="columns">
			<div class="column is-12">
				<div class="content">
					最近は<a href="http://qiita.com/ki6ool" title="Qiita" target="_blank">Qiita</a>に書くことが多いです。
				</div>
			</div>
		</div>
	</div>
</section>

<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
?>
<section class="section">
	<div class="container">
		<div class="columns">
			<div class="column is-12">
				<p class="subtitle4"><?php echo get_the_time('Y-m-d H:i');?></p>
				<h2 class="title">
					<a href="<?php the_permalink();?>"><?php echo $post->post_title;?></a>
          		</h2>
				<div class="content">
					<p><?php the_excerpt();?><br>
				</div>
				<p>Category:&nbsp;<?php the_category(', ');?></p>
				<p>Tag:&nbsp;<?php the_tags('', ', ');?></p>
			</div>
		</div>
	</div>
</section>
<?php
	}

	get_template_part('pagination');
}
?>

<?php get_footer();?>