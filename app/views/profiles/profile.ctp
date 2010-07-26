<div id="content">
	<div id="main_sidebar">
		main sidebar
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
				-->
				<div class="video">
					
				</div>
				<div class="gallery">
					
				</div>
				<div class="picture">
					photo
				</div>
			</div>
			
			
			<div id="profile_summary">
				<table>
					<tr>
						<th class="name" colspan="2">Mr. Bolts</th>
					</tr>
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
				wall
			</div>
			
			
			<div id="profile_sidebar">
				sidebar
			</div>
			
		</div>		
	</div>
		
	<?php echo $this->element('copyright'); ?>	
</div>