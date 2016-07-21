<?php
global $Cmn;

get_header();
get_sidebar();
?>
<div class="tm-main uk-width-medium-3-4">

	<article class="uk-article">
<?php if ( have_posts() ):?>

<?php $i=0; while ( have_posts() ): the_post();?>
<p class="uk-article-lead"><a href="<?php echo get_permalink($post->ID);?>"><?php echo $post->post_title;?></a></p>
<p class="uk-article-meta"><?php the_category('&nbsp;|&nbsp;');?>&nbsp;|&nbsp;<?php echo get_the_time('Y/m/d', $post->ID);?></p>
<hr class="uk-article-divider">

<?php if ( $i==1 ) get_template_part('adsense');?>

<?php $i++; endwhile;?>

<?php echo get_pagination();?>

<?php endif;?>
	</article>

</div><!-- /.tm-main -->
<?php get_footer();?>