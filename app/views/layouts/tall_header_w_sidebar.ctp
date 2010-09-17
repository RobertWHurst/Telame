<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title_for_layout; ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $title_for_layout; ?></title>
<?php
		echo $asset->scripts_for_layout();
		echo $html->meta('favicon.ico', Configure::read('StaticDomain') . '/favicon.ico', array('type' => 'icon'));
		echo $html->meta('viewport', 'width=device-width; initial-scale=1.0; maximum-scale=1.0;');
		echo $html->meta('title', 'Telame');
		echo $html->meta('description', 'Telame: The world\'s first responsible social network');
		echo $html->meta('author', 'Robert Hurst and Eric Friesen');
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
	<script type="text/javascript" src="http://include.reinvigorate.net/re_.js"></script>
	<script type="text/javascript">
		try{
			reinvigorate.track("h8664-j06plyeuf7");
		}
		catch(err){}
	</script>
</body>
</html>