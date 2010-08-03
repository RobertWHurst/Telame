<div class="wall_post clearfix">
	<div class="avatar">
<?php
	$image_url = $html->url(array('controller' => 'media', 'action' => 'avatar', $post['PostAuthor']['id']));
	$url = $html->url(array('controller' => 'users', 'action' => 'profile', $post['PostAuthor']['slug']));
	echo $this->Html->image($image_url, array('url' => $url, 'width' => '60', 'height' => '60'));
?>
	</div>
	<div class="post_content">
		<p>
			<a href="#" title="<?php echo $post['PostAuthor']['UserProfile']['full_name'];?>"><?php echo $post['PostAuthor']['UserProfile']['full_name'];?></a>
<?php			echo $text->autoLink($post['WallPost']['post']); ?>
		</p>
	</div>
	<div class="delete">
<?php 
		$url = $html->url(array('controller' => 'wall_posts', 'action' => 'delete', $post['WallPost']['id']));
		echo $html->image('icons/delete.png', array('title' => __('Delete',true), 'url' => $url));
?>
	</div>
	<div class="wall_to_wall">
<?php 
		$url = $html->url(array('controller' => 'wall_posts', 'action' => 'wall_to_wall', $post['WallPost']['id']));
		$url = '#';
		echo $html->image('icons/comments.png', array('title' => __('Wall to Wall',true), 'url' => $url));
?>
	</div>
	<div class="time">
		<p>
			<?php echo $time->timeAgoInWords($post['WallPost']['posted']); ?>
		</p>
	</div>
</div>