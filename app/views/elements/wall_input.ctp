<?php
$hrl->css(array( 'key' => 'wallInput', 'url' => 'common/wall_input' ));
$hrl->js(array( 'key' => 'wallInput', 'url' => 'common/wall_input', 'requires' => array( 'jQuery' )));
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

?>
	<div class="controls">
		<div class="label"><h3>Scope: </h3></div>
		<div class="acl_scope">
			<?php echo $html->image( '/img/icons/lock.png' ); ?>
		</div>
		<div class="share_scope">
			<?php echo $html->image( '/img/icons/note_go.png' ); ?>
		</div>
		<div class="acl_scope_menu">
			[eric's acl scope here]
		</div>
		<div class="share_scope_menu">
<?php		foreach ($currentUser['Oauth'] as $oauth) {
				if($oauth['method'] == 'write') {
					echo $oauth['service'] . ' ' . $this->Form->input('Oauth.' . $oauth['service'], array('type' => 'checkbox'));
				}
			}
?>
		</div>
	</div>
<?php echo $this->Form->end(__('wall_post_submit', true)); ?>
</div>