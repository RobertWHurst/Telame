<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title_for_layout; ?></title>
	<link rel="alternate" type="application/rss+xml" title="RSS" href="http://<?php echo env('SERVER_NAME')?>/rss/">
<?php			
		if(is_array($css_for_layout)){
			foreach($css_for_layout as $css)
				echo $html->css($css);
		}
		
		echo $html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));
?>
</head>
<body>
	<div id="flash">
		<?php echo $this->Session->flash(); ?>
	</div>
	<div id="backgroundHead"></div>
	<div id="backgroundBase"></div>	
	<div id="wrap_content">
		<div id="content" class="clearfix">
			<div id="wrap_main_sidebar">
				<div id="logo">
					<?php echo $html->image('logo.png', array('title' => __('site_name', true),'url' => array('controller' => 'notifications', 'action' => 'news'))); ?>
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
		<?php echo $this->element('copyright'); ?>
	</div>
	<?php if(Configure::read('debug') > 0): ?>
		<div id="sql_dump">
			<?php echo $this->element('sql_dump'); ?>
		</div>
	<?php endif; ?>
<?php
	if(is_array($script_for_layout)){
		foreach($script_for_layout as $script)
			echo $html->script($script);
	}
?>
</body>
</html>