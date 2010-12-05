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
		<h1>Read</h1>
<?php	foreach ($read as $consumer) {
			$url = array('controller' => 'oauths', 'action' => 'oauth', $consumer['Oauth']['name'], 'read'); ?>
			<h1><?php echo $consumer['Oauth']['name']; ?></h1>
			<p>
<?php			if(in_array($consumer['Oauth']['name'], $connectedServices)) {
					echo $consumer['Oauth']['name'] . ' ' . __('already_connected', true);
					echo $html->link(__('try_again', true), $url);
					echo $html->link(__('disconnect', true), array('controller' => 'services', 'action' => 'disconnect', $consumer['Oauth']['name']));
					echo $html->link(__('scan', true), array('slug' => $currentUser['User']['slug'], 'controller' => 'services', 'action' => 'contacts', $consumer['Oauth']['name']));
					echo __('oauth_expires', true) . ' ' . $consumer['Oauth']['expires'] . ' ' . __('minutes', true);
				} else {
					echo $html->link(__('connect_to_', true) . ' ' . $consumer['Oauth']['name'], $url);
					echo __('oauth_expires', true) . ' ' . $consumer['Oauth']['expires'] . ' ' . __('minutes', true);
				}
			}
?>
		<h1>Write</h1>
<?php	foreach ($write as $consumer) {
			$url = array('controller' => 'oauths', 'action' => 'oauth', $consumer['Oauth']['name'], 'write'); ?>
			<h1><?php echo $consumer['Oauth']['name']; ?></h1>
			<p>
<?php			if(in_array($consumer['Oauth']['name'], $connectedServices)) {
					echo $consumer['Oauth']['name'] . ' ' . __('already_connected', true);
					echo $html->link(__('try_again', true), $url);
					echo $html->link(__('disconnect', true), array('controller' => 'services', 'action' => 'disconnect', $consumer['Oauth']['name']));
					echo __('oauth_expires', true) . ' ' . $consumer['Oauth']['expires'] . ' ' . __('minutes', true);
				} else {
					echo $html->link(__('connect_to_', true) . ' ' . $consumer['Oauth']['name'], $url);
					echo __('oauth_expires', true) . ' ' . $consumer['Oauth']['expires'] . ' ' . __('minutes', true);
				}
			}
?>
		</p>
	</div>
</div>