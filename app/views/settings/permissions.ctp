<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'gallery',
	'summary',
	'tall_header',
	'main_sidebar',
	'settings/settings'
));
$this->set('script_for_layout', array(
	'jquery',
	'base', 
	'main_sidebar',
	'settings/settings'
));
//page title
$this->set('title_for_layout', __('site_name', true) . ' | ' . $user['Profile']['full_name'] . '\'s ' . __('settings', true));
?>
<div id="page_head">
	<h1 class="page_title"><?php echo __('group_permission_title', true); ?></h1>
</div>
<div id="page_navigation" class="clearfix">
	<?php echo $this->element('settings/navigation'); ?>
</div>
<div id="page_body" class="clearfix">
	<div id="permissions">
<?php
		echo $form->create('Acl', array('url' =>  '#'));
			foreach($acoTree as $aco):
?>
				<div class="aco-object">
					<h1>
<?php
						__('permissions_for');
						echo ' ';
						__($aco['Aco']['alias']);
?>
					</h1>
					<?php foreach($aco['Groups'] as $group): ?>					
						<div class="aco-group clearfix <?php echo ($group['Group']['canRead']) ? 'on' : 'off'; ?>">
							<div class="decription">
								<p><?php echo $group['Group']['title']; ?> <?php echo ($group['Group']['canRead']) ? __('can_read_public' ,true) : __('cannot_read_public' ,true); ?></p>
							</div>
							<div class="switch">
<?php 
								$options = array(
									'type' => 'checkbox',
									'id' => "switch_{$group['Group']['id']}"
								);
								
								if($group['Group']['canRead'])
									$options['checked'] = 'checked';
									
								
								echo $form->input(__('public', true), $options);
?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>		
			<?php endforeach; ?>
		<div class="save_changes">
			<?php echo $form->end(__('save_changes', true)); ?>
		</div>
	</div>
	<div id="lists">
		<h1><?php __('lists'); ?></h1>
		<?php foreach($friendLists as $friendList): ?>
			<div id="list-<?php echo $friendList['Group']['id']; ?>" class="list<?php echo ($friendList['selected']) ? ' selected' : '' ; ?>">
				<a href="<?php echo $html->url(array('controller' => 'settings', 'action' => 'permissions', $friendList['Group']['id'])); ?>" title="List"><?php echo $friendList['Group']['title']; ?></a>
			</div>
		<?php endforeach; ?>
	</div>
</div>