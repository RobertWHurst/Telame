<?php
//INCLUDES
$html->css(array(
	'base',
	'tall_header',
	'main_sidebar',
	'messages/messages',
	'messages/compose'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base', 
	'main_sidebar'
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
?>
<div id="page_head" class="clearfix">			
	<h1 class="page_title"><?php echo __('add_friend', true) . ' ' . $friend['User']['full_name']; ?></h1>
</div>
<div id="page_navigation" class="clearfix">
</div>
<div id="page_body" class="clearfix">
	<div id="add_friend">
<?php
		echo $form->create('GroupsUser', array('url' => array('controller' => 'groups_users', 'action' => 'addFriend', $friend['User']['id'])));
		echo $form->input('group_id', array('options' => $friendLists));
		echo $form->hidden('friend_id', array('value' => $friend['User']['id']));
		echo $form->hidden('confirm', array('value' => ($confirm ? true : false)));
		echo $form->hidden('cid', array('value' => $cid));
		echo $form->end(__('add', true));
?>
	</div>
</div>