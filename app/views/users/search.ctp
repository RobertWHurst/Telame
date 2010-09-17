<?php
//INCLUDES
$html->css(array(
	'base',
	'tall_header',
	'main_sidebar',
	'wall_posts',
	'pages/news_feed',
	'pages/news_sidebar'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
);
foreach ($js as $j) {
	$javascript->link($j, false);
}

?>
<div id="page_head">
	<h1 class="page_title"><?php echo __('search_title', true); ?></h1>
</div>
<div id="page_body" class="clearfix">
	<div id="search_results">
<?php
		foreach($results as $user) {
 			pr($user);
		}
?>
	</div>
</div>