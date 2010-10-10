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
$this->set('title_for_layout', __('login', true));
?>
<div id="login_logo">
	<?php echo $html->image('logo.png', array('title' => __('site_name', true), 'url' => array('controller' => 'pages', 'action' => 'home'))); ?>
</div>
<div id="login">
<?php
	echo $session->flash('auth');
	echo $form->create('User', array('action' => 'login'));
	echo $form->input('email', array('label' => __('email', true)));
	echo $form->input('password', array('label' => __('password', true)));
	echo $form->input('remember_me', array('type' => 'checkbox', 'label' => __('auto_login', true)));
	
	//this is above the form submit because it has to float right parallel to the submit button
	echo $html->link(__('password_forget', true), array('controller' => 'users', 'action' => 'passwordReset'), array('class' => 'pass_reset'));
	
	echo $form->end(__('login', true));
?>
</div>
