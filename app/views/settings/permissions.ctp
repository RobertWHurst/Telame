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
		echo $form->create('Acl', array('url' => array('controller' => 'settings', 'action' => 'permissions')));
			foreach($acoTree as $aco):
?>
				<div class="aco-object">
					<h1>
<?php
						echo __('permissions_for', true);
						echo ' ';
						echo __($aco['Aco']['alias'], true);
?>
					</h1>
<?php 				foreach($aco['Groups'] as $group):
						$canRead = $group['Group']['canRead'];
?>
						<div class="aco-group clearfix <?php echo ($canRead) ? 'on' : 'off'; ?>">
							<div class="decription">
								<p><?php
									echo "<strong>{$group['Group']['title']}</strong> ";
									echo $canRead ? __('can_read', true) . ' ' . __($aco['Aco']['alias'] ,true) : __('cannot_read', true) . ' ' . __($aco['Aco']['alias'], true); ?>
								</p>
							</div>
							<div class="switch">
<?php
								echo $form->input($aco['Aco']['alias'] . '.' . $group['Group']['title'], array(
									'type' => 'checkbox',
									'checked' => ($canRead ? 'checked' : ''),
									'label' => __('public', true)
									//'id' => "Aco_{$group['Group']['id']}"
								));
?>
							</div>
						</div>
<?php 				endforeach;
?>
				</div>
			<?php endforeach; ?>
		<div class="save_changes">
			<?php echo $form->end(__('save', true)); ?>
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