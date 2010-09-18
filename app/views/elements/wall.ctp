<div id="profile_wall">
<?php
				echo $this->element('wall_input', array('user' => $user));
?>
	<div id="profile_wall_posts">
<?php
		//if there are posts on the wall then loop through them
		if (!empty($wallPosts) && is_array($wallPosts)):
			foreach ($wallPosts as $post)
				echo $this->element('wall_post', array('post' => $post, 'show_post_controls' => true));
			if(true /*TODO: this needs to check the total number of wall posts a user has*/): ?>
				<!--<div class="more">
<?php 
					$url = $html->url(array('controller' => 'wall_posts', 'action' => 'lists', $user['User']['id']));
					echo $html->link(__('older_posts', true), $url);
?>
				</div>-->
			<?php endif; ?>
		<?php else: ?>
			<p class="no_posts">
				<?php __('wall_empty'); ?>
			</p>
		<?php endif; ?>
	</div>
</div>