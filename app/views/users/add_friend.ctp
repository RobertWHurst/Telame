<?php
echo $form->create('GroupsUser', array('Url' => array('controller' => 'Users', 'action' => 'addFriend')));
echo $form->input('user_id');
echo $form->input('friend_id');
echo $form->input('group_id', array('options' => $friendLists));
echo $form->end(__('add', true));
?>