<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title_for_layout; ?></title>
	<?php echo $html->script('jquery'); ?>
	<?php echo $html->script('external/script'); ?>
	<?php echo $html->css($css_for_layout) ?>
</head>
<body>
	<?php echo $this->element('external/header'); ?>
	<div id="wrap_content">
		<?php echo $content_for_layout; ?>
	</div>
</body>
</html>