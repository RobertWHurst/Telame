<?php
$url = array('controller' => 'users', 'action' => 'profile', $post['PostAuthor']['slug']);
$recipient_name = '<strong>' . $html->link($post['User']['full_name'], $url) . '</strong>';
?>
<p>
<?php echo $recipient_name . __('_posted_a_message_on_', true) . $author_name . '\'s' . __('_wall', true) . ' ' . $html->image('icons/pencil_add.png'); ?>
</p>