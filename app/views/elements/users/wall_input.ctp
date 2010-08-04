<div id="profile_wall_input">

	<!-- borders -->
	<div class="box top"><div class="box top right"></div><div class="box top left"></div></div>
	<div class="box bottom"><div class="box bottom right"></div><div class="box bottom left"></div></div>
	<div class="box right"></div><div class="box left"></div>		
	<!-- end of borders -->
	
	<div class="box inner">
	<?php
		//create the form
		$url = $html->url(array('controller' => 'wall_posts', 'action' => 'add'));	
		echo $this->Form->create('WallPost', array('url' =>  $url));
		echo $this->Form->input('post', array('label' => __('What\'s on your mind?', true), 'type' => 'text'));
		echo $this->Form->hidden('user_id', array('value' => $user['User']['id']));
		echo $this->Form->end(__('Post', true));
	?>
	</div>
</div>