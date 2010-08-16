<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'tall_header',
	'users/login'
));
$this->set('script_for_layout', array(
	//SCRIPT
));
?>
<div id="login_logo">
	<?php echo $html->image('logo.png', array('title' => __('site_name', true), 'url' => array('controller' => 'pages', 'action' => 'home'))); ?>
</div>
<div id="login">
<?php 
	echo $form->create();
	echo $form->input('email');
	echo $form->input('hash');
	echo $form->end(__('confirm', true));
?>
</div>
