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
		<h1>Add new group</h1>
<?php
		echo $form->create('Group', array('url' => $this->here));
		echo $form->input('title');
		echo $form->end(__('save', true));

		foreach($groups as $group) {
			echo $group['Group']['title'];
			if ($group['Group']['user_id'] == $currentUser['User']['id']) {
				echo $html->link(__('delete', true), $this->here . '/' . $group['Group']['id'], null, __('confirm_delete', true) . ' \'' . $group['Group']['title'] . '\'');
			}
			echo '<br />';
		}
?>
	</div>
</div>