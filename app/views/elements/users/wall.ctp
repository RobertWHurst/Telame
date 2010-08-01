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
		if (!empty($user['WallPost']) && is_array($user['WallPost'])):
			foreach ($user['WallPost'] as $post):
?>
				<div class="wall_post clearfix">
					<div class="avatar">
<?php					$image_url = $html->url(array('controller' => 'media', 'action' => 'avatar', $post['PostAuthor']['id']));
						$url = $html->url(array('controller' => 'users', 'action' => 'profile', $post['PostAuthor']['slug']));
   						echo $this->Html->image($image_url, array('url' => $url, 'width' => '60', 'height' => '60'));
?>
					</div>
					<div class="post_content">
						<p>
							<a href="#" title="<?php echo $post['PostAuthor']['full_name'];?>"><?php echo $post['PostAuthor']['full_name'];?></a>
<?php
							echo $post['post'];
?>
						</p>
					</div>
					<div class="delete">
<?php 
						$url = $html->url(array('controller' => 'wall_posts', 'action' => 'delete', $post['id']));
						echo $html->image('icons/delete.png', array('title' => __('Delete',true), 'url' => $url));
?>
					</div>
					<div class="wall_to_wall">
<?php 
						$url = $html->url(array('controller' => 'wall_posts', 'action' => 'wall_to_wall', $post['id']));
						$url = '#';
						echo $html->image('icons/comments.png', array('title' => __('Wall to Wall',true), 'url' => $url));
?>
					</div>
					<div class="time">
						<p>
							<?php echo $time->timeAgoInWords($post['posted']); ?>
						</p>
					</div>
				</div>
			<?php endforeach; ?>
			<div class="more">
<?php 
						$url = $html->url(array('controller' => 'wall_posts', 'action' => 'lists', $user['User']['id']));
						echo $html->link(__('More Wall Posts', true), $url);
?>
			</div>
		<?php else: ?>
			<p class="no_posts">
				<?php __('Sorry, your wall is a bit empty at the moment...'); ?>
			</p>
		<?php endif; ?>
	</div>
</div>