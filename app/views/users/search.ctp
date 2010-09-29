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

$this->Paginator->options(array(
	'url' => array(
		'controller' => 'users',
		'action' => 'search',
		'query' => $this->params['query'],
	)
));
$this->set('title_for_layout', __('search', true));

?>
<div id="page_head">
	<h1 class="page_title"><?php echo __('search_title', true); ?></h1>
</div>
<div id="page_body" class="clearfix">
	<!-- Shows the page numbers -->
	<?php echo $this->Paginator->numbers(); ?>
	<!-- Shows the next and previous links -->
	<?php echo $this->Paginator->prev('Ç Previous'); ?>
	<?php echo $this->Paginator->next('Next È'); ?> 
	<!-- prints X of Y, where X is current page and Y is number of pages -->
	<?php echo $this->Paginator->counter(); ?>

	<div id="search_results">
<?php
		foreach($results as $user) {
 			pr($user);
		}
?>
	</div>
</div>