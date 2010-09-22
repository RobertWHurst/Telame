<div class="current_user_controls clearfix">
	<!-- TODO: once image management is implemented then at a user thumbnail here -->
	<div class="avatar">
<?php
	$image_url = $html->url(array('controller' => 'media', 'action' => 'avatar', $currentUser['User']['avatar_id']));
	$url = $html->url(array('controller' => 'users', 'action' => 'profile', $currentUser['User']['slug']));
	echo $htmlImage->image($image_url, array('url' => $url));
?>
	</div>
	<ul class="controls">
		<li><?php echo $html->link(__('my_profile', true), array('controller' => 'users', 'action' => 'profile', $currentUser['User']['slug'])); ?></li>
		<li><?php echo $html->link(__('account_settings', true), array('slug' => $currentUser['User']['slug'], 'controller' => 'settings', 'action' => 'basic')); ?></li>
		<li><?php echo $html->link(__('logout', true), array('controller' => 'users', 'action' => 'logout')); ?></li>
	</ul>
</div>