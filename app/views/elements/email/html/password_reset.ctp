Somebody  has requested your <?php echo __('site_name', true); ?> account password be reset.  If this wasn't you, just ignore this email and login normally, otherwise follow the link provided.

<?php echo $html->link('Reset Password', 'http://' . env('SERVER_NAME') . '/password_reset/' . $user['User']['email'] . '/' . $temp_password); ?>
