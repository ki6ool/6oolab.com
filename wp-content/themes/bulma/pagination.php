<?php
global $wp_query;
$src = "";
$big = 99999999;
$current = max(1, get_query_var('paged'));
$page_format = paginate_links([
		'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big)) ),
		'format' => '?paged=%#%',
		'current' => $current,
		'total' => $wp_query->max_num_pages,
		'type'  => 'array',
		'prev_text'    => 'Prev',
		'next_text'    => 'Next',
]);
if ( !is_array($page_format) ) return;
$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
?>
<section class="section">
<nav class="pagination">
<?php
$link = [];
$prev = '';
$next = '';
foreach ($page_format as $pf) {
	if ( _matchesIn($pf, 'dots') ) {
		$pf = '<span>&hellip;</span>';
	} else {
		$pf = str_replace(['page-numbers'], 'button', $pf);
		$pf = str_replace(['current'], 'is-primary', $pf);
	}
	if ( _matchesIn($pf, 'prev') ) {
		$prev = "{$pf}\n";
	} elseif ( _matchesIn($pf, 'next') ) {
		$next = "{$pf}\n";
	} else {
		$link[] = $pf;
	}
}
if ( empty($prev) ) {
	$prev = '<a class="button is-disabled">Prev</a>';
} elseif ( empty($next) ) {
	$next = '<a class="button is-disabled">Next</a>';
}
printf("%s\n%s", $prev, $next);
?>
<ul>
<?php
foreach ($link as $li) printf("<li>%s</li>\n", $li);
?>
</ul>
</nav>
</section>
<?php wp_reset_query();?>