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
		<?php if( $user['User']['id'] == $currentUser['User']['id'] ){ ?>
			<div class="label"><h3>Share: </h3></div>
			<div class="icon acl_scope">
				<?php echo $html->image( '/img/icons/lock.png', array( 'title' => 'Permissions' ) ); ?>
			</div>
			<div class="icon share_scope">
				<?php echo $html->image( '/img/icons/note_go.png', array( 'title' => 'Sharing' ) ); ?>
			</div>
			<div class="acl_scope_menu">
<?php			echo $this->Form->input('Group', array(
					'type' => 'select',
					'multiple' => 'checkbox',
					'options' => $groups,
					'selected' => $allowedGroups,
				));
?>
			</div>
			<div class="share_scope_menu">
	<?php		foreach ($currentUser['Oauth'] as $oauth) {
					if($oauth['method'] == 'write') {
						echo $oauth['service'] . ' ' . $this->Form->input('Oauth.' . $oauth['service'], array('type' => 'checkbox'));
					}
				}
	?>
			</div>
		<?php } ?>
		<div class="label"><h3>Attach: </h3></div>
		<div class="icon add_image">
			<?php echo $html->image( '/img/icons/image_add.png', array( 'title' => 'Attach Images' ) ); ?>
		</div>
		<div class="icon add_video">
			<?php echo $html->image( '/img/icons/film_add.png', array( 'title' => 'Attach Videos' ) ); ?>
		</div>
	</div>
<?php echo $this->Form->end(__('wall_post_submit', true)); ?>
</div>