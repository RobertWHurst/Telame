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
<body>
	<header>
		<div id="flash">
			<?php echo $this->Session->flash(); ?>
		</div>
		<div id="backgroundHead"></div>
		<div id="backgroundBase"></div>	
	</header>
	<div id="wrap_content">
		<?php echo $content_for_layout; ?>
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
	<script type="text/javascript" src="http://include.reinvigorate.net/re_.js"></script>
	<script type="text/javascript">
		try{
			reinvigorate.track("h8664-j06plyeuf7");
		}
		catch(err){}
	</script>
</body>
</html>