<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'tall_header',
	'main_sidebar',
	'messages/messages',
	'messages/compose'
));
$this->set('script_for_layout', array(
	'jquery',
	'base',
	'header_glow',
	'main_sidebar'
));
?>
<div id="page_head" class="clearfix">			
	<h1 class="page_title"><?php echo __('add_friend', true) . ' ' . $friend['Profile']['full_name']; ?></h1>
</div>
<div id="page_navigation" class="clearfix">
</div>
<div id="page_body" class="clearfix">
	<div id="add_friend">
<?php
		echo $form->create('GroupsUser', array('Url' => array('controller' => 'Users', 'action' => 'addFriend')));
		echo $form->input('group_id', array('options' => $friendLists));
		echo $form->hidden('friend_id', array('value' => $friend['User']['id']));
		echo $form->end(__('add', true));
?>
	</div>
</div>