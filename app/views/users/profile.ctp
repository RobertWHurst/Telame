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
			$renderProfile->summary($user);
?>
		</div>
		<div id="profile_body" class="clearfix">
<?php 	
			$renderProfile->wall($user);
			echo $this->element('profile/profile_sidebar');
?>
		</div>
	</div>
</div>


<?php echo $this->element('copyright'); ?>