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
		<h1>Facebook</h1>
		<p>
<?php		if(in_array('Facebook', $connectedServices)) {
				echo 'Facebook ' . __('already_connected', true);
				echo $html->link(__('try_again', true), array('controller' => 'oauths', 'action' => 'oauth', 'facebook'));		
			} else {
				echo $html->link(__('connect_to_', true) . ' Facebook', array('controller' => 'oauths', 'action' => 'oauth', 'facebook'));
			}
?>
		</p>
		<h1>Google</h1>
		<p>
<?php		if(in_array('Google', $connectedServices)) {
				echo 'Google ' . __('already_connected', true);
				echo $html->link(__('try_again', true), array('controller' => 'oauths', 'action' => 'oauth', 'google'));		
			} else {
				echo $html->link(__('connect_to_', true) . ' Google', array('controller' => 'oauths', 'action' => 'oauth', 'google'));
			}
?>
		</p>
		<h1>Twitter</h1>
		<p>
<?php		if(in_array('Twitter', $connectedServices)) {
				echo 'Twitter ' . __('already_connected', true);
				echo $html->link(__('try_again', true), array('controller' => 'oauths', 'action' => 'oauth', 'twitter'));		
			} else {
				echo $html->link(__('connect_to_', true) . ' Twitter', array('controller' => 'oauths', 'action' => 'oauth', 'twitter'));
			}
?>
		</p>
		<h1>Yahoo</h1>
		<p>
<?php		if(in_array('Yahoo', $connectedServices)) {
				echo 'Yahoo ' . __('already_connected', true);
				echo $html->link(__('try_again', true), array('controller' => 'oauths', 'action' => 'oauth', 'yahoo'));		
			} else {
				echo $html->link(__('connect_to_', true) . ' Yahoo', array('controller' => 'oauths', 'action' => 'oauth', 'yahoo'));
			}
?>
		</p>
	</div>
</div>