<div id="content" class="clearfix">
	<div id="wrap_main_sidebar">
		<div id="logo">
<?php		
			echo $html->image('users/logo.png', array('title' => __('Telame', true), 'url' => array('controller' => 'pages', 'action' => 'home')));
?>
		</div>
<?php 	
		echo $this->element('users/main_sidebar');
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
<?php 	
			$renderProfile->wall($user);
			echo $this->element('users/profile_sidebar');
?>
		</div>
	</div>
</div>

<?php echo $this->element('copyright'); ?>