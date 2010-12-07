<div class="current_user_controls clearfix">
	<!-- TODO: once image management is implemented then at a user thumbnail here -->
	<div class="avatar">
<?php
	$image_url = $html->url(array('plugin' => null, 'controller' => 'media', 'action' => 'avatar', $currentUser['User']['avatar_id']));
	$url = $html->url(array('controller' => 'users', 'action' => 'profile', $currentUser['User']['slug']));
	echo $htmlImage->image($image_url, array('url' => $url));
?>
	</div>
	<ul class="controls">
		<li><p><?php echo __('hello') . ' ' . $currentUser['User']['first_name'] . '!'; ?></p></li>
		<li><?php echo $html->link(__('account_settings', true), array('plugin' => null, 'slug' => $currentUser['User']['slug'], 'controller' => 'settings', 'action' => 'basic')); ?></li>
<?php if ($currentUser['User']['level'] <= 0) { ?>
		<li><?php echo $html->link(__('admin', true), array('plugin' => null, 'controller' => 'settings', 'action' => 'betakeys', 'admin' => true)); ?></li>
<?php } ?>
		<li><?php echo $html->link(__('logout', true), array('controller' => 'users', 'action' => 'logout')); ?></li>
	</ul>
</div>