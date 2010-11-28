<?php
//INCLUDES
$hrl->css(array(
	array( 'key' => 'newsFeed', 'url' => 'pages/news_feed' ),
	array( 'key' => 'newsSidebar', 'url' => 'pages/news_sidebar' )
));
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