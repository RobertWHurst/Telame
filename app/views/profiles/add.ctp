<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'tall_header',
	'main_sidebar'
));
$this->set('script_for_layout', array(
	'jquery',
	'base',
	'main_sidebar'
));
echo $form->create();
echo $form->inputs();
echo $form->end(__('save', true));
?>