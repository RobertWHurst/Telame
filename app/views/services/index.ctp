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
$this->set('title_for_layout', $currentUser['User']['full_name'] . '\'s ' . __('services', true));
?>
<div id="page_head">
	<h1 class="page_title"><?php echo __('services_title', true); ?></h1>
</div>
<div id="page_navigation" class="clearfix">
	<?php echo $this->element('settings/navigation'); ?>
</div>
<div id="page_body" class="clearfix">
	<div id="services">
<?php	foreach ($consumers as $consumer) { ?>
			<h1><?php echo $consumer['Oauth']['name']; ?></h1>
			<p>
<?php			if(in_array($consumer['Oauth']['name'], $connectedServices)) {
					echo $consumer['Oauth']['name'] . ' ' . __('already_connected', true);
					echo $html->link(__('try_again', true), array('controller' => 'oauths', 'action' => 'oauth', $consumer['Oauth']['name']));
					echo $html->link(__('delete', true), array('controller' => 'oauths', 'action' => 'delete', $consumer['Oauth']['name']));
				} else {
					echo $html->link(__('connect_to_', true) . ' ' . $consumer['Oauth']['name'], array('controller' => 'oauths', 'action' => 'oauth', $consumer['Oauth']['name']));
				}
			}
?>
		</p>
	</div>
</div>