<div class="comment clearfix">
<?php
	if($comment['PostAuthor']['id'] == $currentUser['User']['id'] || $user['User']['id'] == $currentUser['User']['id']):
?>
		<div class="deleteComment">
<?php 
			$url = array('controller' => 'wall_posts', 'action' => 'delete', $comment['id']);
			echo $html->image('icons/delete.png', array('title' => __('delete',true), 'url' => $url));
?>
		</div>
	<?php endif; ?>
	<div class="avatar">
<?php
		$image_url = array('controller' => 'media', 'action' => 'comment', $comment['PostAuthor']['avatar_id']);
		$url = array('controller' => 'users', 'action' => 'profile', $comment['PostAuthor']['slug']);
		echo $this->Html->image($image_url, array_merge(array('url' => $url), Configure::read('CommentSize')));
?>
	</div>
	<div class="content">
<?php
		$author_name = '<strong>' . $html->link($comment['PostAuthor']['full_name'], $url) . ' </strong>';
		//Note: author name is included in the parse because markdown sees
		//it as part of the paragraph and wrapps it within the p tags.
		echo $markdown->parse($author_name . "\r\n" . $comment['post']); 
?>
	</div>
	<div class="time">
		<p>
			<?php echo $time->timeAgoInWords($comment['posted']); ?>
		</p>
	</div>
</div>