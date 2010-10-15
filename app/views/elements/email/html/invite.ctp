You have been invited to <?php echo __('site_name', true); ?>.  <br>
Your beta key is: <?php echo $key['BetaKey']['key']; ?> <br>

Follow the link provided to create an account.  Remember though, the key supplied is only good with this email address. <br>
Thanks for your interest in <?php echo __('site_name', true); ?> <br>

<?php echo $html->link(__('signup_for_', true) . ' ' . __('site_name', true), 'http://' . env('SERVER_NAME') . '/signup/' . $email . '/' . $key['BetaKey']['key']);