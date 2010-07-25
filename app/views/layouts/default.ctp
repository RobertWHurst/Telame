<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title_for_layout; ?></title>
	<?php echo $html->script('jquery'); ?>
	<?php echo $html->css($css_for_layout); ?>
	<?php echo $html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));?>
</head>
<body>
	<div id="backgroundHead"></div>
	<div id="backgroundBase"></div>	
	<div id="wrap_content">
		<?php echo $content_for_layout; ?>
	</div>
</body>
</html>