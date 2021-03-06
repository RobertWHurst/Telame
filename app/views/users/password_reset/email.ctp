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
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
$this->set('title_for_layout', __('password_reset', true));
?>
<div id="login_logo">
	<?php echo $html->image('logo.png', array('title' => __('site_name', true), 'url' => array('controller' => 'pages', 'action' => 'home'))); ?>
</div>
<div id="login">
	<h1 class="heading"><?php echo __('password_reset', true); ?></h1>
<?php
	echo $form->create();
	echo $form->input('email');
	echo $form->end(__('send_reset', true));
?>
</div>