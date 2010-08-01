<div class="current_user_controls clearfix">
	<!-- TODO: once image management is implemented then at a user thumbnail here -->
	<div class="avatar">
<?php
	$image_url = $html->url(array('controller' => 'media', 'action' => 'avatar', $currentUser['User']['id']));
	$url = array('controller' => 'users', 'action' => 'profile', $currentUser['User']['slug']);
	echo $this->Html->image($image_url, array('url' => $url, 'width' => '60', 'height' => '60'));
?>
	</div>
	<ul class="controls">
		<li><?php echo $html->link(__('My Profile', true), array('controller' => 'users', 'action' => 'profile', $currentUser['User']['slug'])); ?></li>
		<li><?php echo $html->link(__('Edit My Profile', true), array('controller' => 'users', 'action' => 'edit', $currentUser['User']['slug'])); ?></li>
		<li><?php echo $html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout')); ?></li>
	</ul>
</div>