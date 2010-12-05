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
<?php
		foreach($contacts as $contact) {
			foreach ($contact['Contact']['email'] as $email) {
				echo $email . ' ' . $contact['Contact']['fullName'];
				echo '<br />';
			}
		}
?>
	</div>
</div>