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
$this->set('title_for_layout', __('signup', true));
?>
<div id="content">

	<?php echo $this->element('pages/taglines'); ?>

	<div id="Signup">
		<h1 class="heading">Signup For Telame</h1>
<?php
		echo $form->create();
		echo $form->input('first_name', array('label' => __('first_name', true)));
		echo $form->input('last_name', array('label' => __('last_name', true)));
		echo $form->input('email');
		echo $form->input('user_password', array('label' => __('password', true), 'type' => 'password'));
		echo $form->input('user_password_again', array('label' => __('password_again', true), 'type' => 'password'));
		echo $form->input('slug', array('label' => __('personal_username', true)));
		echo $form->input('beta_key'); ?>
		<br />
		<p>
			Remember, this is beta software.  We do our best to keep everything working while adding new features and doing bug fixes, but there is always the possibility of us accidentally deleting all of your wall posts, friends, pictures, burning down your house, computer exploding, dog dying, etc... We take no responsibility of any of these things happening from use of Telame.
		</p>
<?php	echo $form->label(__('agree', true));
		echo $form->input('agree', array('label' => false, 'type' => 'checkbox'));
		echo $form->end(__('signup', true));
?>
	</div>

</div>