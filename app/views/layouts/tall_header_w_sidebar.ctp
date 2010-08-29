<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title_for_layout; ?></title>
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
	<div id="backgroundHead">		
		<div id="backgroundHeadGlow"></div>
	</div>
	<div id="backgroundBase"></div>	
	<div id="wrap_content">
		<div id="content" class="clearfix">
			<div id="wrap_main_sidebar">
				<div id="logo">
					<?php echo $html->image('logo.png', array('title' => __('site_name', true),'url' => array('controller' => 'pages', 'action' => 'news'))); ?>
				</div>
				<?php echo $this->element('main_sidebar'); ?>
			</div>
			<div id="page">
				<?php echo $content_for_layout; ?>
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
	echo $html->script('cookie');
?>
</body>
</html>