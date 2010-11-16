<?php
//INCLUDES
$hrl->css(array(
	array( 'key' => 'settings', 'url' => 'settings/common/settings' ),
	array( 'key' => 'slider', 'url' => 'common/slider' ),
	array( 'key' => 'acl_view', 'url' => 'common/acl_view' )
));
$hrl->js(array(
	array( 'key' => 'settings', 'url' => 'settings/common/settings', 'requires' => array( 'base', 'jquery' ) ),
	array( 'key' => 'slider', 'url' => 'common/slider', 'requires' => 'jquery' ),
	array( 'key' => 'acl_view', 'url' => 'common/acl_view', 'requires' => array( 'base', 'jquery', 'slider' ) )
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
	<?php echo $form->create('Acl', array('url' => array('slug' => $currentUser['User']['slug'], 'controller' => 'settings', 'action' => 'groups'))); ?>
		<?php echo $aclView->renderTable( $permissions ); ?>
	<?php echo $form->end( __('save_permissions', true) ); ?>
</div>