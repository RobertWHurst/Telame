Your <?php echo __('site_name', true); ?> account has been created.
To finish setup you must <?php echo $html->link('confirm your email', 'http://' . env('SERVER_NAME') . '/c/' . $user['User']['email'] . '/' . $user['User']['hash']); ?>
<br>
To manually confirm your email, your hash is <?php echo $user['User']['hash']; ?>