<div id="login">
<?php
    echo $form->create('User', array('action' => 'login'));
    echo $form->input('email', array('label' => __('email', true)));
    echo $form->input('password', array('label' => __('password', true)));
	echo $form->input('auto_login', array('type' => 'checkbox', 'label' => __('auto_login', true)));
    echo $form->end(__('login', true));
?>
</div>