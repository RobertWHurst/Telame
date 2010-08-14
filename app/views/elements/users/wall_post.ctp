<div class="wall_post clearfix">
	<div class="avatar">
<?php
		$image_url = array('controller' => 'media', 'action' => 'avatar', $post['PostAuthor']['avatar_id']);
		$url = array('controller' => 'users', 'action' => 'profile', $post['PostAuthor']['slug']);
		echo $this->Html->image($image_url, array('url' => $url, 'width' => '60', 'height' => '60'));
?>
	</div>
	<div class="post_content">
		<?php echo $markdown->parse($html->link($post['PostAuthor']['Profile']['full_name'], $url) . ' ' . $text->autoLink($post['WallPost']['post'])); ?>
	</div>
	<div class="delete">
<?php 
		$url = array('controller' => 'wall_posts', 'action' => 'delete', $post['WallPost']['id']);
		echo $html->image('icons/delete.png', array('title' => __('delete',true), 'url' => $url));
?>
	</div>
	<div class="wall_to_wall">
<?php 
		$url = array('controller' => 'wall_posts', 'action' => 'wall_to_wall', $post['WallPost']['id']);
		$url = '#';
		echo $html->image('icons/comments.png', array('title' => __('wall_to_wall',true), 'url' => $url));
?>
	</div>
	<div class="time">
		<p>
			<?php echo $time->timeAgoInWords($post['WallPost']['posted']); ?>
		</p>
	</div>
</div>