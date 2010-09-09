<div class="wallPostWrap">
	<div class="wallPost clearfix">
		<div class="avatar">
<?php
			$image_url = array('controller' => 'media', 'action' => 'avatar', $post['PostAuthor']['avatar_id']);
			$url = array('controller' => 'users', 'action' => 'profile', $post['PostAuthor']['slug']);
			echo $this->Html->image($image_url, array('url' => $url));
?>
		</div>
		<div class="content">
<?php			
			if(isset($show_user_and_author) && $post['PostAuthor']['id'] != $post['User']['id']){		
				$aUrl = array('controller' => 'users', 'action' => 'profile', $post['User']['slug']);
				$author_name = '<strong>' . $html->link($post['PostAuthor']['Profile']['full_name'], $url) . '</strong> to <strong>' . $html->link($post['User']['Profile']['full_name'], $aUrl) . "</strong>:\r\n\r\n ";				
			}
			else{				
				$author_name = '<strong>' . $html->link($post['PostAuthor']['Profile']['full_name'], $url) . ' </strong>';				
			}
			//Note: author name is included in the parse because markdown sees
			//it as part of the paragraph and wrapps it within the p tags.
			echo $markdown->parse($author_name . "\r\n" . $post['WallPost']['post']);
?>
		</div>
		<?php if(isset($show_post_controls)): ?>
			<?php if($post['PostAuthor']['id'] == $currentUser['User']['id'] || $user['User']['id'] == $currentUser['User']['id']): ?>
				<div class="deletePost">
<?php 
					$url = array('controller' => 'wall_posts', 'action' => 'delete', $post['WallPost']['id']);
					echo $html->image('icons/delete.png', array('title' => __('delete',true), 'url' => $url));
?>
				</div>
			<?php endif; ?>
			<!-- This is depricated
			<div class="wall_to_wall">
< ?php 
				//$url = array('controller' => 'wall_posts', 'action' => 'wall_to_wall', $post['WallPost']['id']);
				//$url = '#';
				//echo $html->image('icons/comments.png', array('title' => __('wall_to_wall',true), 'url' => $url));
? >
			</div> -->
		<?php endif; ?>
		<div class="baseline">			
			<?php if(isset($show_post_controls)): ?>
				<div class="baseline_controls">
<?php 	
					if($post['WallPost']['like']){
						$text = __('like', true) . " ({$post['WallPost']['like']})";
						$classes = "like liked";
					}
					else{
						$text = __('like', true);
						$classes = "like";
					}
					echo $html->link($text, array('controller' => 'wall_posts', 'action' => 'like', $post['WallPost']['id']), array('class' => $classes));
					
					if($post['WallPost']['dislike']){
						$text = __('dislike', true) . " ({$post['WallPost']['dislike']})";
						$classes = "dislike disliked";
					}
					else{
						$text = __('dislike', true);
						$classes = "dislike";
					}
					echo $html->link($text, array('controller' => 'wall_posts', 'action' => 'dislike', $post['WallPost']['id']), array('class' => $classes));
					
					echo $html->link(__('comment', true), '', array('class' => 'showComments'));
?>
				</div>			
			<?php endif; ?>
			<div class="baseline_info">
<?php
				if($post['WallPost']['like'] || $post['WallPost']['dislike']):
					$like = " {$post['WallPost']['like']}";
					$like .= ' ' .	__n('person_likes_this', 'people_like_this', $post['WallPost']['like'], true);
			
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
	<div class="commentsWrap">
		<div class="arrow_up"></div>		
		<div class="comments">	
<?php 
			if($post['Replies']){
				foreach($post['Replies'] as $comment){
					
					$vars['comment'] = $comment;
					if(isset($show_post_controls))
						$vars['show_post_controls'] = $show_post_controls;
					
					echo $this->element('wall_post_comment', $vars);
				}
			}
			if(isset($show_post_controls)):
?>
				<div class="commentInput">
<?php
					//create the form
					$url = $html->url(array('controller' => 'wall_posts', 'action' => 'add'));	
					echo $form->create('WallPost', array('url' =>  $url, 'id' => "WallPost_{$post['WallPost']['id']}"));
						echo $form->input('post', array('label' => __('comment', true), 'type' => 'text', 'id' => "WallPostPost_{$post['WallPost']['id']}"));
						echo $form->hidden('user_id', array('value' => $user['User']['id'], 'id' => "WallPostComment_{$post['WallPost']['id']}"));
						echo $form->hidden('reply_parent_id', array('value' => $post['WallPost']['id'], 'id' => "WallPostReplyParentId_{$post['WallPost']['id']}"));
					echo $form->end(__('wall_post_submit', true));
?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>