<?php
//INCLUDES
$hrl->css(array( 'key' => 'profileSidebar', 'url' => 'users/common/profile_sidebar' ));

//page title
$this->set('title_for_layout', $user['User']['full_name']);

echo $this->element('wall'); ?>
<div id="wall_sidebar">
	<?php echo $this->element('users/friends'); ?>
</div>