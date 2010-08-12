<?php
echo $form->create('Message', array('url' =>  array('controller' => 'messages', 'action' => 'send_message')));
echo $form->hidden('user_id', array('value' => $composer['user_id']));
echo $form->hidden('author_id', array('value' => $composer['author_id']));
echo $form->hidden('parent_id', array('value' => $composer['parent_id']));
echo $form->textarea('content');
echo $form->end('Post Reply');