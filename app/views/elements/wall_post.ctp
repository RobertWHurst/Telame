<div class="wall_post_wrap">
    <div class="<?php echo $post['WallPost']['class']; ?> clearfix">
<?php
    	$url = array('controller' => 'users', 'action' => 'profile', $post['PostAuthor']['slug']);
    	if($post['WallPost']['type'] == 'post' || $post['WallPost']['type'] == 'media'):
?>
    		<div class="avatar">
<?php
    			$image_url = array('controller' => 'media', 'action' => 'avatar', $post['PostAuthor']['avatar_id']);
    			echo $html->image($image_url, array_merge(array('url' => $url), Configure::read('AvatarSize')));
?>
    		</div>
    	<?php endif; ?>
    	<div class="content">
<?php
    			if(isset($show_user_and_author) && $post['PostAuthor']['id'] != $post['User']['id']){
    				$aUrl = array('controller' => 'users', 'action' => 'profile', $post['User']['slug']);
    				$author_name = '<strong>' . $html->link($post['PostAuthor']['full_name'], $url) . '</strong> to <strong>' . $html->link($post['User']['full_name'], $aUrl) . "</strong>:\r\n\r\n ";
    			}
    			else{
    				$author_name = '<strong>' . $html->link($post['PostAuthor']['full_name'], $url) . ' </strong>';
    			}
    			echo $this->element('wall_post_types/' . $post['WallPost']['type'], array('author_name' => $author_name, 'post' => $post));
?>
    	</div>
    	<?php if($post['PostAuthor']['id'] == $currentUser['User']['id'] || $post['User']['id'] == $currentUser['User']['id']): ?>
    		<div class="deletePost">
<?php
    			$url = array('slug' => $currentUser['User']['slug'], 'controller' => 'wall_posts', 'action' => 'delete', $post['WallPost']['id']);
    			echo $html->image('icons/delete.png', array('title' => __('delete',true), 'url' => $url));
?>
    		</div>
<?php
    	endif;
    	if($post['WallPost']['type'] == 'post' || $post['WallPost']['type'] == 'media'):
?>
    		<div class="baseline">
    			<div class="baseline_controls">
<?php
    				if($post['WallPost']['like']){
    					$text = __('like', true) . ' (' . $post['WallPost']['like'] . ')';
    					$classes = "like liked";
    				}
    				else{
    					$text = __('like', true);
    					$classes = "like";
    				}
    				echo $html->link($text, array('slug' => $currentUser['User']['slug'], 'controller' => 'wall_posts', 'action' => 'like', $post['WallPost']['id']), array('class' => $classes));

    				if($post['WallPost']['dislike']){
    					$text = __('dislike', true) . ' (' . $post['WallPost']['dislike'] . ')';
    					$classes = "dislike disliked";
    				}
    				else{
    					$text = __('dislike', true);
    					$classes = "dislike";
    				}
    				echo $html->link($text, array('slug' => $currentUser['User']['slug'], 'controller' => 'wall_posts', 'action' => 'dislike', $post['WallPost']['id']), array('class' => $classes));

    				echo $html->link(__('comment', true), '', array('class' => 'showComments'));
?>
    			</div>
    	    	<div class="baseline_info">
<?php
    				if($post['WallPost']['like'] || $post['WallPost']['dislike']):
    					$like = ' ' . $post['WallPost']['like'];
    					$like .= ' ' .	__n('person_likes_this', 'people_like_this', $post['WallPost']['like'], true);

    					$dislike = ' ' . $post['WallPost']['dislike'];
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
    	<?php endif; ?>
    </div>
    <?php if($post['WallPost']['type'] == 'post' || $post['WallPost']['type'] == 'media'): ?>
    	<div class="commentsWrap">
    		<div class="arrow_up"></div>
    		<div class="comments">
<?php
    			if($post['Replies']){
    				foreach($post['Replies'] as $comment)
    					echo $this->element('wall_post_comment', compact('comment'));
    			}
?>
    			<div class="commentInput">
<?php
    				//create the form
    				$url = $html->url(array('slug' => $currentUser['User']['slug'], 'controller' => 'wall_posts', 'action' => 'add'));
    				echo $form->create('WallPost', array('url' =>  $url, 'id' => 'WallPost_' . $post['WallPost']['id']));
    					echo $form->input('post', array('label' => __('comment', true), 'type' => 'text', 'id' => 'WallPostPost_' . $post['WallPost']['id']));
    					echo $form->hidden('user_id', array('value' => $post['User']['id'], 'id' => 'WallPostComment_' . $post['WallPost']['id']));
    					echo $form->hidden('reply_parent_id', array('value' => $post['WallPost']['id'], 'id' => 'WallPostReplyParentId_' . $post['WallPost']['id'] ));
    				echo $form->end(__('wall_post_submit', true));
?>
    			</div>
    		</div>
    	</div>
    <?php endif; ?>
</div>
