<div class="current_user_controls clearfix">
	<!-- TODO: once image management is implemented then at a user thumbnail here -->
	<div class="avatar"></div>
	<ul class="controls">
		<li><?php echo $html->link(__('My Profile', true), $html->url(array('controller' => 'users', 'action' => 'profile'))); ?></li>
		<li><?php echo $html->link(__('Logout', true), $html->url(array('controller' => 'users', 'action' => 'logout'))); ?></li>
	</ul>
</div>