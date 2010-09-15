<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title_for_layout; ?></title>
	<link rel="alternate" type="application/rss+xml" title="RSS" href="http://<?php echo env('SERVER_NAME')?>/rss/">
<?php
		echo $asset->scripts_for_layout();
		echo $html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));
?>
</head>
<?php ob_flush(); ?>
<body>
	<?php echo $this->element('flash'); ?>
	<div id="backgroundHead">
		<div id="backgroundHeadGlow"></div>
	</div>
	<div id="backgroundBase"></div>
	<div id="wrap_content">
		<div id="content" class="clearfix">
			<div id="wrap_main_sidebar">
				<div id="logo">
					<?php echo $htmlImage->image('logo.png', array('title' => __('site_name', true),'url' => array('controller' => 'pages', 'action' => 'news'), 'static' => true)); ?>
				</div>
				<?php echo $this->element('main_sidebar'); ?>
			</div>
			<div id="page">
				<div id="page_head" class="clearfix">
<?php
					//render the profile gallery
					echo $this->element('gallery');

					//render the profile summary
					echo $this->element('summary');
?>
				</div>
				<div id="page_navigation" class="clearfix">
					<?php echo $this->element('profile_navigation'); ?>
				</div>
				<div id="page_body" class="clearfix">
					<?php echo $content_for_layout; ?>
				</div>
			</div>
		</div>
		<footer>
			<?php echo $this->element('copyright'); ?>
		</footer>
	</div>
	<?php if(Configure::read('debug') > 0): ?>
		<div id="sql_dump">
			<?php echo $this->element('sql_dump'); ?>
		</div>
	<?php endif; ?>
</body>
</html>