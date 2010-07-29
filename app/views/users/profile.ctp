<div id="content">
	<div id="wrap_main_sidebar">
		<div id="logo">
<?php		
			echo $html->image('profile/logo.png', array('title' => __('Telame', true), 'url' => array('controller' => 'pages', 'action' => 'home')));
?>
		</div>
<?php 	
		echo $this->element('profile/main_sidebar');
		echo $this->element('profile/friends');
?>
	</div>
	<div id="profile">
		<div id="profile_head" class="clearfix">
<?php		
			//render the profile gallery
			$renderProfile->gallery($user);
			
			//render the profile summary
			if (isset($edit) && $edit) {
				$renderProfile->edit($user);
			} else {
	 			$renderProfile->summary($user);
			}
?>
		</div>
		<div id="profile_body" class="clearfix">
			<div id="profile_wall">
				<div id="profile_wall_input">
<?php 				
					echo $form->create('WallPost', array('url' => '/wall_posts/add/'));
					echo $form->input('post', array('label' => __('What\'s on your mind?', true), 'type' => 'text'));
					echo $form->hidden('user_id', array('value' => $user['User']['id']));
					echo $form->end(__('Post', true));
?>
				</div>
<?php
				if (!empty($user['WallPost'])) {
					foreach ($user['WallPost'] as $post) {
						echo $html->link($post['PostAuthor']['slug'], '/p/' . $post['PostAuthor']['slug']) . ' says: ';
						echo $post['post'];
						echo $html->image('icons/delete.png', array(
							'title' => __('Delete',true), 
							'url' => '/wall_posts/delete/' . $post['id'])
						);
						echo $time->timeAgoInWords($post['posted']);
						echo '<br /><hr /><br />';
					}
				} else {
					echo __('You don\' have any posts; what gives?');
				}
?>
			</div>
<?php 	
			echo $this->element('profile/profile_sidebar');
?>
		</div>
	</div>
</div>


<?php echo $this->element('copyright'); ?>