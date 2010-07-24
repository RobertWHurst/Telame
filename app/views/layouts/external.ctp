<?php echo $html->docType('xhtml11'); ?> 
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title_for_layout; ?></title>
	<?php echo $html->script('jquery'); ?>
	<?php echo $html->script('external/script'); ?>
	<?php echo $html->css($css_for_layout); ?>
	<?php echo $html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));?>
	<?php echo $html->meta('description', 'Telame is a social ');?>
</head>
<body>
	<?php echo $this->element('external/header'); ?>
	<div id="wrap_content">
		<?php echo $content_for_layout; ?>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
