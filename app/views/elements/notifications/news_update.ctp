<div class="update clearfix">
	<div class="avatar">
<?php
		$image_url = array('controller' => 'media', 'action' => 'avatar', $update['PostAuthor']['id']);
		$url = array('controller' => 'users', 'action' => 'profile', $update['PostAuthor']['slug']);
		echo $this->Html->image($image_url, array('url' => $url, 'width' => '60', 'height' => '60'));
?>
	</div>
	<div class="update_content">
		<p>
			<a href="/<?php echo $update['PostAuthor']['slug']; ?>" title="<?php echo $update['PostAuthor']['Profile']['full_name']; ?>">
				<?php echo $update['PostAuthor']['Profile']['full_name'];?>
			</a> => <a href="/<?php echo $update['User']['slug']?>" title="<?php echo $update['User']['Profile']['full_name']; ?>">
				<?php echo $update['User']['Profile']['full_name']; ?>
			</a>
			<?php echo $update['WallPost']['post']; ?>
		</p>
	</div>
	<div class="hide">
<?php 
		//$url = $html->url(array('controller' => 'wall_posts', 'action' => 'delete', $update['WallPost']['id']));
		$url = '#';
		echo $html->image('icons/delete.png', array('title' => __('Delete',true), 'url' => $url));
?>
	</div>
	<div class="time">
		<p>
			<?php echo $time->timeAgoInWords($update['WallPost']['posted']); ?>
		</p>
	</div>
</div>