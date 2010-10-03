<?php
$url = array('controller' => 'users', 'action' => 'profile', $post['ActionRecipient']['slug']);
$recipient_name = '<strong>' . $html->link($post['ActionRecipient']['full_name'] . '\'s', $url) . '</strong>';
?>
<p>
<?php echo $author_name . __('_posted_a_message_on_', true) . $recipient_name . __('_wall', true) . ' ' . $html->image('icons/pencil_add.png'); ?>
</p>