<div id="login">
<?php
    echo $form->create('User', array('action' => 'login'));
    echo $form->input('email', array('label' => __('Email')));
    echo $form->input('password', array('label' => __('Password')));
	echo $form->input('auto_login', array('type' => 'checkbox', 'label' => __('Log me in automatically?')));
    echo $form->end(__('Login'));
?>
</div>