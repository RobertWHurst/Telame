<div class="current_user_controls">
	<!-- TODO: once image management is implemented then at a user thumbnail here -->
	<div class="avatar"></div>
	<ul class="controls">
		<li><?php echo $html->link(__('My Profile'), '/profiles/profile'); ?></li>
		<li><?php echo $html->link(__('Logout'), '/users/logout'); ?></li>
	</ul>
</div>