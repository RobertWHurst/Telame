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
<?php		
		if(isset($script_for_layout))
			$head_types['script'] = $script_for_layout;
			
		if(isset($css_for_layout))
			$head_types['css'] = $css_for_layout;
		
		foreach($head_types as $method => $file){
			if(is_array($file)){
				foreach($file as $_file)
					echo $html->$method($_file);					
			}
			else{
				echo $html->$method($file);
			}
		}
		
		echo $html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));
?>
</head>
<body>
	<div id="flash">
		<?php echo $this->Session->flash(); ?>
	</div>
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
	<div id="wrap_content">
		<?php echo $content_for_layout; ?>
	</div>
</body>
</html>
