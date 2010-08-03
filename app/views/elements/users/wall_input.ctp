<div id="profile_wall_input">
<?php
	//create the form
	$url = $html->url(array('controller' => 'wall_posts', 'action' => 'add'));
	echo $this->Form->create('WallPost', array('url' =>  $url));
	echo $this->Form->input('post', array('label' => __('What\'s on your mind?', true), 'type' => 'text'));
	echo $this->Form->hidden('user_id', array('value' => $user['User']['id']));
	echo $this->Form->end(__('Post', true));
?>
</div>