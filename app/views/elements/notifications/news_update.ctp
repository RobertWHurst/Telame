<div class="update clearfix">
	<div class="avatar">
<?php
		$image_url = array('controller' => 'media', 'action' => 'avatar', $update['PostAuthor']['avatar_id']);
		$url = array('controller' => 'users', 'action' => 'profile', $update['PostAuthor']['slug']);
		echo $this->Html->image($image_url, array('url' => $url, 'width' => '60', 'height' => '60'));
?>
	</div>
	<div class="update_content">
		<p>
			<?php if($update['PostAuthor']['id'] != $update['User']['id']): ?>
				<a href="/<?php echo $update['PostAuthor']['slug']; ?>" title="<?php echo $update['PostAuthor']['Profile']['full_name']; ?>"><?php echo $update['PostAuthor']['Profile']['full_name']; ?></a>
				> <a href="/<?php echo $update['User']['slug']?>" title="<?php echo $update['User']['Profile']['full_name']; ?>"><?php echo $update['User']['Profile']['full_name']; ?></a>: <br/>
			<?php else: ?>
				<a href="/<?php echo $update['PostAuthor']['slug']; ?>" title="<?php echo $update['PostAuthor']['Profile']['full_name']; ?>"><?php echo $update['PostAuthor']['Profile']['full_name']; ?></a> 
			<?php endif; ?>
			<?php echo $update['WallPost']['post']; ?>
		</p>
	</div>
	<!--
	<div class="hide">
<?php 
		//$url = $html->url(array('controller' => 'wall_posts', 'action' => 'delete', $update['WallPost']['id']));
		$url = '#';
		echo $html->image('icons/delete.png', array('title' => __('delete',true), 'url' => $url));
?>
	</div>
	-->
	<div>
<?php 	// I would have made these ajax if we were using the Ajax helper, since it would be $ajax->link instead of $html->link and be done with it, but since you're writing all the javascript yourself I don't know what the fuck I'm doing, so I will leave it up to you to fix.
		echo $html->link('Like', array('controller' => 'wall_posts', 'action' => 'like', $update['WallPost']['id']));
		if ($update['WallPost']['like']) {
			echo $html->image('icons/thumb_up.png') . ' ' . $update['WallPost']['like'] . ' ' .  __n('person_likes_this', 'people_like_this', $update['WallPost']['like'], true);
		}
		echo '<br />';
		if ($update['WallPost']['dislike']) {
			echo $html->image('icons/thumb_down.png') . ' ' . $update['WallPost']['dislike'] . ' ' .  __n('person_dislikes_this', 'people_dislike_this', $update['WallPost']['dislike'], true);
		}

		echo $html->link('Disike', array('controller' => 'wall_posts', 'action' => 'dislike', $update['WallPost']['id'])) 
?>
	</div>
	<div class="time">
		<p>
			<?php echo $time->timeAgoInWords($update['WallPost']['posted']); ?>
		</p>
	</div>
</div>