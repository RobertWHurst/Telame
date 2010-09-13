<!DOCTYPE HTML>
<html lang="en-US">
<head>

	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">	
	
	<title><?php echo $title_for_layout; ?></title>
<?php			
		echo $html->css($css_for_layout);		
		echo $html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));
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
		<div id="wrap_header">
			<div id="header">
				<div id="logo">
					<?php echo $html->image('pages/logo.png', array('title' => __('site_name', true), 'url' => array('controller' => 'signup', 'action' => 'index'))); ?>
				</div>
				<div class="headerRight">
					<?php echo $this->element('pages/navigation'); ?>
				</div>
			</div>
		</div>
	</header>
	<div id="wrap_content">
		<?php echo $content_for_layout; ?>
	</div>
	<?php echo $html->script($script_for_layout); ?>
	<script type="text/javascript" src="http://include.reinvigorate.net/re_.js"></script>
	<script type="text/javascript">
		try{
			reinvigorate.track("h8664-j06plyeuf7");
		}
		catch(err){}
	</script>
</body>
</html>
