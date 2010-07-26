<div id="content">
	<div id="wrap_main_sidebar">
		<div id="logo">
			<?php echo $html->image('profile/logo.png', array('title' => 'Telame', 'url' => array('controller' => 'profiles', 'action' => 'profile'))); ?>
		</div>
		<div id="main_sidebar">
			main sidebar
		</div>
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
					<?php echo $html->image('1.png', array('title' => 'Mr. Bolts')); ?>
				</div>
			</div>
			
			
			<div id="profile_summary">
				<h1 class="name">Mr. Bolts</h1>
				<table>
					<tr>
						<th class="key"><?php echo __('Location'); ?>:</th><td class="value">Telame Core</td>
					</tr>
					<tr>
						<th class="key"><?php echo __('Tela Group'); ?>:</th><td class="value">System Bots</td>
					</tr>
					<tr>
						<th class="key"><?php echo __('Sex'); ?>:</th><td class="key">None</td>
					</tr>
					<tr>
						<th class="key"><?php echo __('Born'); ?>:</th><td class="key">June 23, 2010</td>
					</tr>
					<tr>
						<th class="key"><?php echo __('Joined'); ?>:</th><td class="key">At Core Execution</td>
					</tr>
				</table>
			</div>
			
			
		</div>	
		<div id="profile_body" class="clearfix">
		
		
			<div id="profile_wall">
				debug => <?php krumo($debug); ?>
			</div>
			
			
			<div id="profile_sidebar">
				sidebar
			</div>
			
		</div>		
	</div>
</div>
		

<?php echo $this->element('copyright'); ?>	