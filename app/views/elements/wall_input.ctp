<?php
$hrl->css(array( 'key' => 'wallInput', 'url' => 'common/wall_input' ));
$hrl->js(array( 'key' => 'wallInput', 'url' => 'common/wall_input', 'requires' => array( 'jQuery', 'wallPost' )));
?>
<div id="wall_input" class="clearfix">
<?php

	$url = $html->url(array(
		'slug' => $currentUser['User']['slug'],
		'controller' => 'wall_posts',
		'action' => 'add'
	));

	echo $this->Form->create( 'WallPost', array( 'url' =>  $url ) );

		if( $user['User']['id'] == $currentUser['User']['id'] ){
			$label = __( 'wall_post_label_personal', true );
		} else {
			$label = __('wall_post_label', true);
		}

		echo $this->Form->input('post', array('label' => $label, 'type' => 'text'));
		echo $this->Form->hidden('user_id', array('value' => $user['User']['id']));
	
	echo $this->Form->end(__('wall_post_submit', true));
?>
	<div class="controls">
		[acl scope], [share scope]
	</div>
</div>