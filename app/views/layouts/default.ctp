<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title_for_layout; ?></title>
<?php		
		if(isset($js_for_layout))
			$head_types['script'] = $js_for_layout;
			
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
	<div id="backgroundHead"></div>
	<div id="backgroundBase"></div>	
	<div id="wrap_content">
<?php 
		echo $content_for_layout;
?>
	</div>
<?php 
		if(Configure::read('debug') > 0):
?>
			<div id="sql_dump">
<?php
				echo $this->element('sql_dump');
?>
			</div>
<?php 
		endif; 
?>
	</div>
</body>
</html>