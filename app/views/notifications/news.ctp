<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'tall_header',
	'main_sidebar',
	'wall_posts',
	'notifications/news_feed',
	'notifications/news_sidebar'
));
$this->set('script_for_layout', array(
	'jquery',
	'base'
));
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
		foreach($wallPosts as $update)
			echo $this->element('wallPost', array('post' => $update));
?>
	</div>
	<?php echo $this->element('notifications/news_sidebar', array('friendLists' => $friendLists)); ?>
</div>