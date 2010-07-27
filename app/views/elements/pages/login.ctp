<div id="login">
<?php
    echo $form->create('User', array('action' => 'login'));
    echo $form->input('email', array('label' => __('Email', true)));
    echo $form->input('password', array('label' => __('Password', true)));
	echo $form->input('auto_login', array('type' => 'checkbox', 'label' => __('Log me in automatically?', true)));
    echo $form->end(__('Login'));
?>
</div>