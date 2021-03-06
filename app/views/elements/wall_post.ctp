<?php
	$hrl->css(array( 'key' => 'wallPost', 'url' => 'common/wall_post' ));
	$hrl->js(array(
		array( 'key' => 'wallPost', 'url' => 'common/wall_post', 'requires' => 'jQuery' ),
		array( 'key' => 'wallCommentInput', 'url' => 'common/wall_comments_input', 'requires' => array( 'jQuery' ))
	));
?>
<div id="post_<?php echo 'wall_post' . '_' . $post['WallPost']['id']; ?>" class="post">
	<div class="inner_post wall_post clearfix">
<?php
		$url = array('controller' => 'users', 'action' => 'profile', $post['PostAuthor']['slug']);
?>
		<div class="avatar">
<?php
			$image_url = array('controller' => 'media', 'action' => 'avatar', $post['PostAuthor']['avatar_id']);
			echo $html->image($image_url, array_merge(array('url' => $url), Configure::read('AvatarSize')));
?>
		</div>
		<div class="content">
<?php
				if(isset($show_user_and_author) && $show_user_and_author && $post['PostAuthor']['id'] != $post['User']['id']) {
					$aUrl = array('controller' => 'users', 'action' => 'profile', $post['User']['slug']);
					$author_name = '<strong>' . $html->link($post['PostAuthor']['full_name'], $url) . '</strong> to <strong>' . $html->link($post['User']['full_name'], $aUrl) . "</strong>\r\n\r\n ";
				} else {
					$author_name = '<strong>' . $html->link($post['PostAuthor']['full_name'], $url) . '</strong>';
				}
				echo $this->element('wall_post_types/post', array('author_name' => $author_name, 'post' => $post));
?>
		</div>
<?php
		if(
			$post['WallPost']['author_id'] == $currentUser['User']['id'] ||
			$post['WallPost']['user_id'] == $currentUser['User']['id']
		){
?>
			<div class="delete_post">
<?php
				$url = array('slug' => $currentUser['User']['slug'], 'controller' => 'wall_posts', 'action' => 'delete', $post['WallPost']['id']);
				echo $html->image('icons/delete.png', array('title' => __('delete',true), 'url' => $url));
?>
			</div>
		<?php } ?>
		<div class="baseline">
			<div class="baseline_controls">
<?php
				if( ! $post['WallPost']['like']){
					$text = __('like', true);
					$classes = "like";
				} else {
					$text = __('unlike', true);
					$classes = "like liked";
				}

				echo $html->link($text, array('slug' => $currentUser['User']['slug'], 'controller' => 'wall_posts', 'action' => 'like', $post['WallPost']['id']), array('class' => $classes));

				if( ! $post['WallPost']['dislike']){
					$text = __('dislike', true);
					$classes = "dislike";
				} else {
					$text = __('undislike', true);
					$classes = "dislike disliked";
				}

				echo $html->link($text, array('slug' => $currentUser['User']['slug'], 'controller' => 'wall_posts', 'action' => 'dislike', $post['WallPost']['id']), array('class' => $classes));

				echo $html->link(__('comment', true), '', array('class' => 'showComments'));
?>
			</div>
			<div class="baseline_info">
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
			<?php if( ! empty($post['WallPostLike'] ) ){ ?>
				<div class="likeness">
<?php 
					//set defaults
					$likes = $dislikes = array();

					foreach($post['WallPostLike'] as $like){

						if($like['like']){
							if($like['User']['id'] == $currentUser['User']['id']){
								array_unshift($likes, array(
									'name' => __('you', true),
									'slug' => $like['User']['slug']
								));
							} else {
								$likes[] = array(
									'name' => $like['User']['full_name'],
									'slug' => $like['User']['slug']
								);
							}
						} else {
							if($like['User']['id'] == $currentUser['User']['id']){
								array_unshift($dislikes, array(
									'name' => __('you', true),
									'slug' => $like['User']['slug']
								));
							} else {
								$dislikes[] = array(
									'name' => $like['User']['full_name'],
									'slug' => $like['User']['slug']
								);
							}
						}
					}
?>
					<?php if( ! empty($likes)){ ?>
						<div class="Likes clearfix">
							<h3><?php __('liked_by_'); ?></h3>
							<ul>
								<?php foreach($likes as $like){ ?>
									<li>
<?php 
										echo $html->link($like['name'], array(
											'controller' => 'users',
											'action' => 'profile',
											$like['slug']
										));
?>
									</li>
								<?php } ?>
							</ul>
						</div>
					<?php } ?>
					<?php if(!empty($dislikes)){ ?>
						<div class="dislikes clearfix">
							<h3><?php __('disliked_by_'); ?></h3>
							<ul>
								<?php foreach($dislikes as $dislike){ ?>
									<li>
<?php 
										echo $html->link($dislike['name'], array(
											'controller' => 'users',
											'action' => 'profile',
											$dislike['slug']
										));
?>
									</li>
								<?php } ?>
							</ul>
						</div>
					<?php } ?>
				</div>
<?php
			}
			if($post['Replies']){
				foreach($post['Replies'] as $comment)
					echo $this->element('wall_post_comment', compact('comment'));
			}
?>
			<div class="comment_input">
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
</div>
