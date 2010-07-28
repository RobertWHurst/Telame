<div id="content">
	<div id="wrap_main_sidebar">
		<div id="logo">
<?php		echo $html->image('profile/logo.png', array('title' => __('Telame', true), 'url' => array('controller' => 'pages', 'action' => 'home')));
?>
		</div>
<?php 	echo $this->element('profile/main_sidebar');
?>
	</div>
	<div id="profile">
		<div id="profile_head" class="clearfix">

			<div id="profile_gallery">
				<!--
					javascript will only load one of the following items.

					if javascript sees a video wrapper, it will setup an html5
					video interface to play the video inside.

					if it finds a gallery wrapper it will create a gallery
					slideshow.

					if it finds a picture wrapper it will just show an avatar.

					<div class="video">

					</div>
					<div class="gallery">

					</div>
				-->
				<div class="picture">
<?php 				echo $html->image($user['User']['slug'] . '.png', array('title' => $user['ProfileMeta']['first_name'] . $user['ProfileMeta']['last_name']));
?>
				</div>
			</div>

<?php		// render_profile helper
			$renderProfile->summary($user);
?>
		</div>
		<div id="profile_body" class="clearfix">
			<div id="profile_wall">
				<div id="profile_wall_input">
<?php 				echo $form->create('WallPost', array('url' => '/wall_posts/add/'));
					echo $form->input('post', array('label' => __('What\'s on your mind?', true), 'type' => 'text'));
					echo $form->end(__('Post', true)); 
?>
				</div>
<?php
				if (!empty($user['WallPost'])) {
				foreach ($user['WallPost'] as $post) {
						echo $post['post'];
						echo '<br /><hr /><br />';
					}
				} else {
					echo __('You don\' have any posts; what gives?');
				}
?>
			</div>


			<div id="profile_sidebar">
				sidebar
			</div>

		</div>
	</div>
</div>


<?php echo $this->element('copyright'); ?>