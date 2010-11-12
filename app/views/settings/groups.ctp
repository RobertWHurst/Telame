<?php
//INCLUDES
$hrl->css(array(
	array(
		'key' => 'settings',
		'url' => 'settings/settings',
	),
	array(
		'key' => 'slider',
		'url' => 'slider'
	)
));
$hrl->js(array(
	array( 'key' => 'settings', 'url' => 'settings/settings', 'requires' => array( 'base', 'jquery' ) ),
	array( 'key' => 'telasource.slider', 'url' => 'telasource.slider', 'requires' => array( 'jquery' ) )
));
//page title
$this->set('title_for_layout', $currentUser['User']['full_name'] . '\'s ' . __('settings', true));
?>
<div id="page_head">
	<h1 class="page_title"><?php echo __('groups_title', true); ?></h1>
</div>
<div id="page_navigation" class="clearfix">
	<?php echo $this->element('settings/navigation'); ?>
</div>
<div id="page_body" class="clearfix">
	----------
<form action="" method="post">
	<div id="slider_test">
		<div id="slider">

			<label for="slider-allow">Allow</label>
			<input id="slider-allow" type="radio" name="slider" value="allow">

			<label for="slider-block">Block</label>
			<input id="slider-block" type="radio" name="slider" value="block" checked>

			<label for="slider-inherit">Default</label>
			<input id="slider-inherit" type="radio" name="slider" value="inherit">

		</div>


		<div id="slid">

			<label for="slid-allow">Allow</label>
			<input id="slid-allow" type="radio" name="slid" value="allow">

			<label for="slid-block">Block</label>
			<input id="slid-block" type="radio" name="slid" value="block" checked>

		</div>
	</div>
</form>
	----------
	<div id="group_permissions">
		<div class="groups_list clearfix">
			<h1><?php echo __('groups', true); ?></h1>
<?php
			foreach($groups as $group):
				if($group['Group']['id'] == $currentGroup['Group']['id'])
					$classes = 'group current';
				else
					$classes = 'group';
?>
				<div class="<?php echo $classes; ?>">
<?php				$gurl = array('slug' => $currentUser['User']['slug'], 'controller' => 'settings', 'action' => 'groups', $group['Group']['id']);
					echo $html->link($group['Group']['title'], $gurl, array('class' => 'button'));
?>
					<div class="delete">
<?php					$durl = array('slug' => $currentUser['User']['slug'], 'controller' => 'groups', 'action' => 'deleteGroup', $group['Group']['id']);
						if ($group['Group']['user_id'] == $currentUser['User']['id']) {
							echo $html->image('icons/delete.png', array('title' => __('delete',true), 'url' => $durl));
						}
?>
					</div>
					<p>[stats in link to list of friends...]</p>
				</div>
			<?php endforeach; ?>
			<div id="add_new_group">
<?php			echo $form->create('Group', array('url' => array('slug' => $currentUser['User']['slug'], 'controller' => 'groups', 'action' => 'addGroup')));
				echo $form->input('title');
				echo $form->end(__('save', true));
?>
			</div>
		</div>
		<div class="group_permissions">
			<?php if(is_array($permissions)):
				echo $this->Form->create('Acl', array('url' => array('slug' => $currentUser['User']['slug'], 'controller' => 'settings', 'action' => 'groups')));
				$this->Acl->displayAcoTree($permissions);
				echo $this->Form->end(__('save', true));
			else: ?>
				<p class="empty"><?php __('select_group_for_permissions'); ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>