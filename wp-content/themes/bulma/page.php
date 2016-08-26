<?php get_header();?>

<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
?>
<section class="section">
	<div class="container">
		<div class="columns">
			<div class="column is-12">
				<div class="content">
					<p><?php the_content();?><br>
				</div>

<?php if ( $post->post_name == 'zundokokiyoshi' && class_exists('ZundokoKiyoshi')) new ZundokoKiyoshi();?>

			</div>
		</div>
	</div>
</section>
<?php
	}
?>

<?php
}
?>

<?php get_footer();?>