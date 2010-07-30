<div class="current_user_controls clearfix">
	<!-- TODO: once image management is implemented then at a user thumbnail here -->
	<div class="avatar"></div>
	<ul class="controls">
		<li><?php echo $html->link(__('My Profile', true), '/' . $currentUser['User']['slug']); ?></li>
		<li><?php echo $html->link(__('Edit My Profile', true), '/e/' . $currentUser['User']['slug']); ?></li>
		<li><?php echo $html->link(__('Logout', true), '/logout'); ?></li>
	</ul>
</div>