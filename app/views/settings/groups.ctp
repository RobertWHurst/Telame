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
	array( 'key' => 'acl_view', 'url' => 'common/acl_view', 'requires' => array( 'base', 'jQuery', 'slider' ) )
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
		//echo $aclView->renderTable( $permissions );
?>
		<div id="test" style="padding:10px; background:#333;">
<?php
			echo $this->Form->input(
				'test',
				array(
					'type' => 'radio',
					'default' => 1,
					'options' => array(
						'0' => __('block', true),
						'1' => __('allow', true),
						'2' => __('inherit', true)
					),
					'legend' => false
				)
			);
?>
		</div>
		<div id="test2" style="padding:10px; background:#333;">
<?php
			echo $this->Form->input(
				'test2',
				array(
					'type' => 'radio',
					'default' => 2,
					'options' => array(
						'0' => __('block', true),
						'1' => __('allow', true),
						'2' => __('inherit', true)
					),
					'legend' => false
				)
			);
?>
		</div>
		<div id="test3" style="padding:10px; background:#333;">
<?php
			echo $this->Form->input(
				'test3',
				array(
					'type' => 'radio',
					'default' => 0,
					'options' => array(
						'0' => __('block', true),
						'1' => __('allow', true),
						'2' => __('inherit', true)
					),
					'legend' => false
				)
			);
?>
		</div>
		<div id="test4" style="padding:10px; background:#333;">
<?php
			echo $this->Form->input(
				'test4',
				array(
					'type' => 'radio',
					'default' => 2,
					'options' => array(
						'0' => __('block', true),
						'1' => __('allow', true),
						'2' => __('inherit', true)
					),
					'legend' => false
				)
			);
?>
		</div>
<?php

	echo $form->end( __('save_permissions', true) );
?>
</div>