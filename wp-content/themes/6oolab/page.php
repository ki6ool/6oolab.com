<?php
get_header();
get_sidebar();

the_post();
?>
<div class="tm-main uk-width-medium-3-4">

	<article class="uk-article">

		<p class="uk-article-lead"><?php echo $post->post_title;?></p>

		<?php get_template_part('adsense');?>

		<p><?php the_content();?></p>

<?php if ( $post->post_name == 'zundokokiyoshi' && class_exists('ZundokoKiyoshi')) new ZundokoKiyoshi();?>

	</article>

</div><!-- /.tm-main -->
<?php get_footer();?>