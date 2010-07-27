<div id="content">
	<div id="wrap_main_sidebar">
		<div id="logo">
			<?php echo $html->image('profile/logo.png', array('title' => __('Telame', true), 'url' => array('controller' => 'pages', 'action' => 'home'))); ?>
		</div>
		<?php echo $this->element('profile/main_sidebar'); ?>
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
					<?php echo $html->image($profile['Profile']['slug'] . '.png', array('title' => $profile['ProfileMeta']['first_name'] . $profile['ProfileMeta']['last_name'])); ?>
				</div>
			</div>
			
			<?php // render_profile helper
			$renderProfile->summary($profile); 
			?>
			
			
		</div>	
		<div id="profile_body" class="clearfix">
		
		
			<div id="profile_wall">
				<?php 
				echo $form->create('WallPost');
				echo $form->input('post', array('label' => __('What\'s on your mind?', true)));
				echo $form->end(__('Post', true));
				foreach ($wallPosts as $post) {
					echo $post['WallPost']['post'];
					echo '<br /><hr /><br />';
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