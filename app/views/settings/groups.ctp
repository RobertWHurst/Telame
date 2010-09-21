<?php
//INCLUDES
$html->css(array(
	'base',
	'gallery',
	'summary',
	'tall_header',
	'main_sidebar',
	'settings/settings'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
	'main_sidebar',
	'settings/settings'
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
//page title
$this->set('title_for_layout', __('site_name', true) . ' | ' . $user['User']['full_name'] . '\'s ' . __('settings', true));
?>
<div id="page_head">
	<h1 class="page_title"><?php echo __('groups_title', true); ?></h1>
</div>
<div id="page_navigation" class="clearfix">
	<?php echo $this->element('settings/navigation'); ?>
</div>
<div id="page_body" class="clearfix">
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
<?php
					$gurl = array('controller' => 'settings', 'action' => 'groups', $group['Group']['id']);
					echo $html->link($group['Group']['title'], $gurl, array('class' => 'button'));
?>
					<div class="delete">
<?php
						$durl = array('controller' => 'settings', 'action' => 'delete_group', $group['Group']['id']);
						echo $html->image('icons/delete.png', array('title' => __('delete',true), 'url' => $durl));
?>
					</div>
					<p>[stats in link to list of friends...]</p>
				</div>
			<?php endforeach; ?>
			<div id="add_new_group">
				[new group input]
			</div>
		</div>
		<div class="group_permissions">
			<?php if(is_array($permissions)): ?>
				<h1><?php echo __('permissions_for', true) . ' "' . $currentGroup['Group']['title'] . '"'; ?></h1>
				<table>
					<?php foreach($permissions as $aco): ?>
						<tr>
							<td>
								<?php echo $form->label(__('this_group_can_view_your', true) . ' "' . __($aco['alias'], true) . '"'); ?>
							</td>
							<td>
								<?php echo $form->input("canRead[{$aco['id']}][{$currentGroup['Group']['id']}]", array('type' => 'radio', 'default' => $aco['canRead'], 'options' => array('no' => __('no', true), 'yes' => __('yes', true)))); ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php else: ?>
				<p class="empty"><?php __('select_group_for_permissions'); ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>