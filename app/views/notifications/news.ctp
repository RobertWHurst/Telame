<div id="content" class="clearfix">
	<div id="wrap_main_sidebar">
		<div id="logo">
			<!-- TODO: link to the news feed -->
			<?php echo $html->image('users/logo.png', array('title' => __('Telame', true),'url' => array('controller' => 'pages', 'action' => 'signup'))); ?>
		</div>
		<?php echo $this->element('main_sidebar'); ?>
	</div>
	<div id="page">
		<div id="page_head">
			<h1 class="page_title">What's New</h1>
			<?php echo $html->link('Get your news via RSS', '#', array('title' => 'Get your news via RSS', 'class' => 'rss_link')); ?>
		</div>
		<div id="page_body">
			<div id="news_feed">
<?php
				foreach($wallPosts as $update)
					echo $this->element('notifications/news_update', array('update' => $update));
?>
			</div>			
			<div id="news_sidebar">
				sidebar
			</div>
		</div>
	</div>
</div>
<?php echo $this->element('copyright'); ?>