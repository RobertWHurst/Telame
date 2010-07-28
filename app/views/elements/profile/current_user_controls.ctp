<div class="current_user_controls clearfix">
	<!-- TODO: once image management is implemented then at a user thumbnail here -->
	<div class="avatar"></div>
	<ul class="controls">
		<li><?php echo $html->link(__('My Profile', true), '/profiles/profile'); ?></li>
		<li><?php echo $html->link(__('Logout', true), '/users/logout'); ?></li>
	</ul>
</div>