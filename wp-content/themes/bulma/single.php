<?php get_header();?>

<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
?>
<section class="section">
	<div class="container">

		<div class="content">
			<p><?php the_content();?><br>
		</div>

<?php get_template_part("sns");?>

	</div>
</section>
<?php
	}
?>

<hr>

<section class="section">
	<div class="container">
		<div class="content">
<p>Category:&nbsp;<?php the_category(', ');?></p>
<p>Tag:&nbsp;<?php the_tags('', ', ');?></p>
		</div>
	</div>
</section>

<?php get_template_part("relate");?>

<?php get_template_part("disqus");?>

<?php
}
?>

<?php get_footer();?>