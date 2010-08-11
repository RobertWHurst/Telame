<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'simple_header',
	'users/signup'
));
$this->set('script_for_layout', array(
	'users/taglines'
));
?>
<div id="content">
	
	<?php echo $this->element('pages/taglines'); ?>	
	
	<div id="Signup">
		<h1 class="heading">Signup For Telame</h1>
<?php 
		echo $form->create();
		echo $form->input('email');
		echo $form->input('password');
		echo $form->input('passwd', array('label' => 'Password again'));
		echo $form->input('slug', array('label' => 'Personal Username'));
		echo $form->end(__('signup', true));
?>	
	</div>
	
</div>