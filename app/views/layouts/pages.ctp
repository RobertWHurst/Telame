<!-- 
	This would be better, but doesn't contain the HTML5 Doctype below this comment
	<php echo $html->docType('xhtml11'); ?>
	
	We will need html 5 for our video players and editors. (these fall back to html4).
-->
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title_for_layout; ?></title>
	<?php echo $html->script('jquery'); ?>
	<?php echo $html->script('pages/script'); ?>
	<?php echo $html->css($css_for_layout); ?>
	<?php echo $html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));?>
	<?php echo $html->meta('description', 'Telame is a social ');?>
</head>
<body>
	<?php echo $this->element('pages/header'); ?>
	<div id="wrap_content">
		<?php echo $content_for_layout; ?>
	</div>
</body>
</html>
