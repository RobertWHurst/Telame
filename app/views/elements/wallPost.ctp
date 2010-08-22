<div class="wallPostWrap">
	<div class="wallPost clearfix">
		<div class="avatar">
<?php
			$image_url = array('controller' => 'media', 'action' => 'avatar', $post['PostAuthor']['avatar_id']);
			$url = array('controller' => 'users', 'action' => 'profile', $post['PostAuthor']['slug']);
			echo $this->Html->image($image_url, array('url' => $url, 'width' => '60', 'height' => '60'));
?>
		</div>
		<div class="content">
			<?php echo $markdown->parse($html->link($post['PostAuthor']['Profile']['full_name'], $url) . ' ' . $text->autoLink($post['WallPost']['post'])); ?>
		</div>
		<?php if(isset($show_post_controls)): ?>
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
		<?php endif; ?>
		<div class="baseline">
			<div class="baseline_controls">
<?php 	
				$like = ($post['WallPost']['like'])? __('like', true) . " ({$post['WallPost']['like']})" : 'Like';
				$dislike = ($post['WallPost']['dislike'])? __('dislike', true) . " Dislike ({$post['WallPost']['dislike']})" : 'Dislike';
							
				echo $html->link($like, array('controller' => 'wall_posts', 'action' => 'like', $post['WallPost']['id']));
				echo $html->link($dislike, array('controller' => 'wall_posts', 'action' => 'dislike', $post['WallPost']['id']));

				echo $html->link(__('comment', true), '#', array('class' => 'showComments'));
?>
			</div>
			<div class="baseline_info">
<?php
				if($post['WallPost']['like'] || $post['WallPost']['dislike']):
					$like = " {$post['WallPost']['like']}";
					$like .= ' ' .  __n('person_likes_this', 'people_like_this', $post['WallPost']['like'], true);
			
					$dislike = " {$post['WallPost']['dislike']}";
					$dislike .= ' ' .  __n('person_dislikes_this', 'people_dislike_this', $post['WallPost']['dislike'], true);
?>
					<p><?php echo ($post['WallPost']['like'])? $like : '' ; ?></p>
					<p><?php echo ($post['WallPost']['dislike'])? $dislike : '' ; ?></p>
				<?php endif; ?>
			</div>
			<div class="time">
				<p>
					<?php echo $time->timeAgoInWords($post['WallPost']['posted']); ?>
				</p>
			</div>
		</div>
	</div>
<?php 
	/*
	$post['Replies'][] = array(
		'WallPost' => array(
			'id' => '#',
			'post' => 'This is a test comment. It is hard cored and not part of the db.'
		),
		'PostAuthor' => array(
			'Profile' => array(
				'full_name' => 'Mr. Bolts X08'
			)
		)
	);
	$post['Replies'][] = array(
		'WallPost' => array(
			'id' => '#',
			'post' => 'Just one more comment to see how two comments look together.'
		),
		'PostAuthor' => array(
			'Profile' => array(
				'full_name' => 'Mr. Bolts X34'
			)
		)
	); 
	*/
?>
	<div class="commentsWrap">
		<div class="arrow_up"></div>		
		<div class="comments">	
			<?php if($post['Replies']): foreach($post['Replies'] as $comment): ?>
				<div class="comment">
					<?php if(isset($show_post_controls)): ?>
						<div class="delete">
<?php 
							$url = array('controller' => 'wall_posts', 'action' => 'delete', $comment['WallPost']['id']);
							echo $html->image('icons/delete.png', array('title' => __('delete',true), 'url' => $url));
?>
						</div>
					<?php endif; ?>
					<div class="content">
						<?php echo $markdown->parse($html->link($comment['PostAuthor']['Profile']['full_name'], $url) . ' ' . $text->autoLink($comment['WallPost']['post'])); ?>
					</div>
					<div class="time">
						<p>
							<?php echo $time->timeAgoInWords($post['WallPost']['posted']); ?>
						</p>
					</div>
				</div>	
			<?php endforeach; endif; ?>	
			<div class="input">
<?php
				//create the form
				$url = $html->url(array('controller' => 'wall_posts', 'action' => 'add'));	
				echo $this->Form->create('WallPost', array('url' =>  $url));
					echo $this->Form->input('post', array('label' => __('comment', true), 'type' => 'text'));
					echo $this->Form->hidden('user_id', array('value' => $user['User']['id']));
				echo $this->Form->end(__('wall_post_submit', true));
?>
			</div>
		</div>
	</div>
</div>