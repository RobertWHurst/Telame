<div id="login">
<?php
    echo $session->flash('auth');
    echo $form->create('Profile', array('action' => 'login'));
    echo $form->input('email');
    echo $form->input('password');
    echo $form->end('Login');
?>
</div>