<?php
//INCLUDES
$html->css(array(
	'base',
	'tall_header',
	'main_sidebar'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
	'main_sidebar'
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
echo $form->create();
echo $form->inputs();
echo $form->end(__('save', true));
?>