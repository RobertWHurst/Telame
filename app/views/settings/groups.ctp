<?php
//INCLUDES
$hrl->css(array(
	array( 'key' => 'settings', 'url' => 'settings/common/settings' ),
	array( 'key' => 'lock_switch', 'url' => 'common/lock_switch' ),
	array( 'key' => 'acl_view', 'url' => 'common/acl_view' )
));
$hrl->js(array(
	array( 'key' => 'settings', 'url' => 'settings/common/settings', 'requires' => array( 'base', 'jQuery' ) ),
	array( 'key' => 'lock_switch', 'url' => 'common/lock_switch', 'requires' => 'jQuery' ),
	array( 'key' => 'acl_view', 'url' => 'common/acl_view', 'requires' => array( 'base', 'jQuery', 'lock_switch' ) )
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
<?php
	echo $form->create('Acl', array('url' => array('slug' => $currentUser['User']['slug'], 'controller' => 'settings', 'action' => 'groups')));
		echo $aclView->renderTable( $permissions );
	echo $form->end( __('save_permissions', true) );
?>
</div>