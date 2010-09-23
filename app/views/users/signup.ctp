<?php
//INCLUDES
$html->css(array(
	'base',
	'simple_header',
	'users/signup'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base', 
	'users/taglines'
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
?>
<div id="content">
	
	<?php echo $this->element('pages/taglines'); ?>	
	
	<div id="Signup">
		<h1 class="heading">Signup For Telame</h1>
<?php 
		echo $form->create();
		echo $form->input('email');
		echo $form->input('user_password', array('label' => __('password', true), 'type' => 'password'));
		echo $form->input('user_password_again', array('label' => __('password_again', true), 'type' => 'password'));
		echo $form->input('slug', array('label' => 'Personal Username'));
		echo $form->input('beta_key');
		echo $form->end(__('signup', true));
?>	
	</div>
	
</div>