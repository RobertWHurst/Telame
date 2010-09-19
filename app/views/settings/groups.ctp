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
	<div id="permissions">
		<div class="options_group clearfix">
		<h1><?php echo __('add_group', true); ?></h1>
<?php
			echo $form->create('Group', array('url' => $this->here));
			echo $form->input('title');
			echo $form->end(__('add_group', true));
?>
		</div>
		<div class="options_group clearfix">
			<h1><?php echo __('groups', true); ?></h1>
			<?php foreach($groups as $group): ?>
				<div class="group">
					<h3><?php echo $group['Group']['title']; ?></h3>
<?php
					$url = array('controller' => 'settings', 'action' => 'groups', $group['Group']['id']);
					echo $html->link(__('delete', true), $url);
?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>