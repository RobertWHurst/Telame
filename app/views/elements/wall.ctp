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
			if($this->params['action'] != 'news'): ?>
				<div class="more">
<?php 
					$url = $html->url(array('controller' => 'wall_posts', 'action' => 'more_posts', $user['User']['id']));
					echo $html->link(__('older_posts', true), $url, array('class' => 'more'));
?>
				</div>
			<?php endif; ?>
		<?php else: ?>
			<p class="no_posts">
				<?php __('wall_empty'); ?>
			</p>
		<?php endif; ?>
	</div>
</div>