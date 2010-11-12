<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo __('site_name', true) . ' | ' . $title_for_layout; ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $title_for_layout; ?></title>
<?php
	echo $html->meta('favicon.ico', Configure::read('StaticDomain') . '/favicon.ico', array('type' => 'icon'));
	echo $html->meta('viewport', 'width=device-width; initial-scale=1.0; maximum-scale=1.0;');
	echo $html->meta('title', 'Telame');
	echo $html->meta('description', 'Telame: The world\'s first responsible social network');
	echo $html->meta('author', 'Robert Hurst and Eric Friesen');

	$hrl->css(array(
		array( 'key' => 'base', 'url' => 'base' ),
		array( 'key' => 'tall_header', 'url' => 'tall_header' ),
		array( 'key' => 'main_sidebar', 'url' => 'main_sidebar' ),
	));
	$hrl->js(array(
		array( 'key' => 'jquery', 'url' => 'jquery' ),
		array( 'key' => 'base', 'url' => 'base', 'requires' => 'jquery' ),
		array( 'key' => 'main_sidebar', 'url' => 'main_sidebar', 'requires' => array( 'base', 'jquery' ) )
	));

	//$hrl->merge = false;

	$hrl->css();
	$hrl->js();
?>
</head>
<?php ob_flush(); ?>
<body>
	<header>
		<?php echo $this->element('flash'); ?>
		<div id="backgroundHead"></div>
		<div id="backgroundBase"></div>
	</header>
	<div id="wrap_content">
		<div id="content" class="clearfix">
			<div id="wrap_main_sidebar">
				<div id="logo">
					<?php echo $htmlImage->image('logo.png', array('title' => __('site_name', true),'url' => array('controller' => 'pages', 'action' => 'news'), 'static' => true)); ?>
				</div>
				<?php echo $this->element('main_sidebar'); ?>
			</div>
			<div id="page">
				<?php echo $content_for_layout; ?>
			</div>
		</div>
		<footer>
			<footer>
			<?php echo $this->element('copyright'); ?>
		</footer>
		</footer>
	</div>
	<?php if(Configure::read('debug') > 0): ?>
		<div id="sql_dump">
			<?php echo $this->element('sql_dump'); ?>
		</div>
	<?php endif; ?>
	<?php echo $this->element('analytics'); ?>
	<div id="modal_screen"></div>
<?php

	$hrl->print_log( true );
?>
</body>
</html>