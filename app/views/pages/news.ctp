<?php
//INCLUDES
$html->css(array(
	'base',
	'tall_header',
	'main_sidebar',
	'wall_posts',
	'pages/news_feed',
	'pages/news_sidebar',
	'wall'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
	'wall',
	'wall_input'
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
$this->set('title_for_layout', __('news_title', true));
?>
<div id="page_head">
	<h1 class="page_title"><?php echo __('news_title', true); ?></h1>
	<?php if(!empty($user['User']['rss_hash'])) {
		echo $html->link(
			'Get your news via RSS',
			'/rss/0/' . $currentUser['User']['id'] . '/' . $user['User']['rss_hash'] . '.rss',
			array('title' => 'Get your news via RSS', 'class' => 'rss_link'));
		} ?>
</div>
<div id="page_body" class="clearfix">
	<div id="news_feed">
<?php
		echo $this->element('wall', array('wallPosts' => $wallPosts, 'show_user_and_author' => true));
?>
	</div>
	<?php echo $this->element('pages/news_sidebar', array('friendLists' => $friendLists)); ?>
</div>