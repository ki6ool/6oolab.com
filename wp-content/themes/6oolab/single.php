<?php
global $Cmn, $cat;
get_header();
get_sidebar();

the_post();
?>
<div class="tm-main uk-width-medium-3-4">

	<article class="uk-article">

		<ul class="uk-breadcrumb">
		    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/" itemprop="url"><span itemprop="title">ホーム</span></a></li>
<?php $cats = get_the_category($post->ID); if ( !empty($cats) ): $cat = reset($cats);?>
		    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/archives/category/<?php echo $cat->slug;?>" itemprop="url"><span itemprop="title"><?php echo $cat->name;?></span></a></li>
<?php endif;?>
		    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="uk-active"><span itemprop="title"><?php echo $post->post_title;?></span></li>
		</ul>

		<p class="uk-article-lead"><?php echo $post->post_title;?></p>

		<p class="uk-article-meta"><?php echo get_the_time('Y/m/d', $post->ID);?></p>

<?php get_template_part('adsense');?>

		<p><?php the_content();?></p>

<ul class="uk-pagination">
<?php $prev = get_previous_post(); if ( !empty($prev) ):?>
<li class="uk-pagination-previous"><a href="/archives/<?php echo $prev->ID;?>"><i class="uk-icon-angle-double-left"></i>&nbsp;<?php echo $prev->post_title;?></a></li>
<?php endif;?>
<?php $next = get_next_post(); if ( !empty($next) ):?>
<li class="uk-pagination-next"><a href="/archives/<?php echo $next->ID;?>">&nbsp;<?php echo $next->post_title;?>&nbsp;<i class="uk-icon-angle-double-right"></i></a></li>
<?php endif;?>
</ul>

		<hr class="uk-article-divider">

<p>Category:&nbsp;<?php the_category(', ');?></p>
<p>Tag:&nbsp;<?php the_tags('', ', ');?></p>

<?php get_template_part("relate");?>

<?php get_template_part("disqus");?>

	</article>

</div><!-- /.tm-main -->
<?php get_footer();?>