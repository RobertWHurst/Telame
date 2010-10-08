<?php
//INCLUDES
$html->css(array(
	'base',
	'gallery',
	'summary',
	'tall_header',
	'main_sidebar',
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
	'main_sidebar',
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
//page title
$this->set('title_for_layout', __('admin_settings', true));
?>
<div id="page_head">
	<h1 class="page_title"><?php echo __('admin_settings', true); ?></h1>
</div>
<div id="page_navigation" class="clearfix">
	<?php echo $this->element('settings/admin/navigation'); ?>
</div>
<div id="page_body" class="clearfix">
	<div id="basic">
		<div id="admin_settings">
			<div class="options_group clearfix">
				<h1>Send email invite</h1>
<?php			echo $form->create('Email', array('url' => array('admin' => true, 'controller' => 'settings', 'action' => 'inviteemail')));
				echo $form->input('address', array('label' => __('email_address', true)));
				echo $form->end(__('send', true));
?>
			</div>
			<div class="options_group clearfix">
				<h1>Generate New Keys</h1>
<?php			echo $form->create('BetaKey', array('url' => array('admin' => true, 'controller' => 'settings', 'action' => 'generatekeys')));
				echo $form->input('howmany', array('label' => __('keys_how_many', true)));
				echo $form->end(__('keys_generate', true));
?>
			</div>
			<div class="options_group clearfix">
				<h1>Beta Keys</h1>
				<table>
<?php foreach($keys as $key): ?>
					<tr>
						<td class="field"><?php echo $key['BetaKey']['key']; ?></td>
						<td class="field"><?php echo $key['BetaKey']['email']; ?></td>
						<td class="field"><?php echo $html->link(__('delete', true), '/admin/bkdelete/' . $key['BetaKey']['id']); ?></td>
					</tr>
<?php endforeach; ?>
				</table>
			</div>
		</div>
	</div>
</div>
