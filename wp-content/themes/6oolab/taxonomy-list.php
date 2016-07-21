<?php
global $Cmn;

$object = get_queried_object();
$taxonomy = $object->slug;
$row = ( $taxonomy == "category" )
	? get_categories(array('orderby' => 'count', 'order' => 'desc'))
	: get_tags(array('orderby' => 'count', 'order' => 'desc'));


get_header();
get_sidebar();
?>
<div class="tm-main uk-width-medium-3-4">

<h3 class="tm-article-subtitle"><?php echo $object->name;?></h3>

<ul class="uk-list uk-list-line">
<?php if ( !empty($row) ): foreach ($row as $r):?>
<li><a href="/archives/<?php echo "{$taxonomy}/{$r->slug}";?>"><?php echo "{$r->name} ({$r->count})";?></a></li>
<?php endforeach; endif;?>
</ul>

</div><!-- /.tm-main -->
<?php get_footer();?>