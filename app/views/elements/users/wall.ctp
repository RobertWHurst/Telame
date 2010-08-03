<div id="profile_wall">
	<div id="profile_wall_input">
<?php
		//create the form
		$url = $html->url(array('controller' => 'wall_posts', 'action' => 'add'));
		echo $this->Form->create('WallPost', array('url' =>  $url));
		echo $this->Form->input('post', array('label' => __('What\'s on your mind?', true), 'type' => 'text'));
		echo $this->Form->hidden('user_id', array('value' => $user['User']['id']));
		echo $this->Form->end(__('Post', true));
?>
	</div>
	<div id="profile_wall_posts">
<?php
		//if there are posts on the wall then loop through them
		if (!empty($wallPosts) && is_array($wallPosts)):
			foreach ($wallPosts as $post){
				echo $this->element('users/wall_post', array('post' => $post));
			}
?>
			<?php if(true /*TODO: this needs to check the total number of wall posts a user has*/): ?>
				<div class="more">
<?php 
					$url = $html->url(array('controller' => 'wall_posts', 'action' => 'lists', $user['User']['id']));
					echo $html->link(__('Older Posts', true), $url);
?>
				</div>
			<?php endif; ?>
		<?php else: ?>
			<p class="no_posts">
				<?php __('Sorry, your wall is a bit empty at the moment...'); ?>
			</p>
		<?php endif; ?>
	</div>
</div>