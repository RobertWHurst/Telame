<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'tall_header',
	'users/login'
));
$this->set('script_for_layout', array(	
	'jquery',
	'base',
	'header_glow'
));
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
	echo $form->input('auto_login', array('type' => 'checkbox', 'label' => __('auto_login', true)));
    echo $form->end(__('login', true));
?>
</div>