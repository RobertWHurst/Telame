<div id="login_logo">
	<?php echo $html->image('pages/logo.png', array('title' => 'Telame', 'url' => array('controller' => 'pages', 'action' => 'home'))); ?>
</div>
<div id="login">
<?php
    echo $session->flash('auth');
    echo $form->create('User', array('action' => 'login'));
    echo $form->input('email', array('label' => __('Email')));
    echo $form->input('password', array('label' => __('Password')));
	echo $form->input('auto_login', array('type' => 'checkbox', 'label' => __('Log me in automatically?')));
    echo $form->end(__('Login'));
?>
</div>