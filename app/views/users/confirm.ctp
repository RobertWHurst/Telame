<?php
//INCLUDES
$html->css(array(
	'base',
	'tall_header',
	'users/login'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base'
	//SCRIPT
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
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
