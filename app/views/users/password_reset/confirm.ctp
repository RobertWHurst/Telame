<?php
//INCLUDES
$html->css(array(
	'base',
	'simple_header',
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
$this->set('title_for_layout', __('password_reset', true));
?>
<div id="content">
	<div id="password_reset">
		<h1 class="heading"><?php echo __('password_reset', true); ?></h1>
<?php
		echo $form->create();
		echo $form->input('email');
		echo $form->input('temp_password');
		echo $form->end(__('confirm', true));
?>
	</div>
</div>