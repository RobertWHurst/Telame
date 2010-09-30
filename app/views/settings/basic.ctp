<?php
//INCLUDES
$html->css(array(
	'base',
	'gallery',
	'summary',
	'tall_header',
	'main_sidebar',
	'settings/settings',
	'settings/gallery_options'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
	'gallery_options',
	'main_sidebar',
	'settings/settings',
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
//page title
$this->set('title_for_layout', $currentUser['User']['full_name'] . '\'s ' . __('settings', true));
?>
<div id="page_head">
	<h1 class="page_title"><?php echo __('settings', true); ?></h1>
</div>
<div id="page_navigation" class="clearfix">
	<?php echo $this->element('settings/navigation'); ?>
</div>
<div id="page_body" class="clearfix">
	<div id="basic">
		<div id="profile_options">
<?php
			echo $form->create('Profile', array('url' => array('slug' => $currentUser['User']['slug'], 'controller' => 'settings', 'action' => 'basic')));
?>
			<div class="options_group clearfix">
				<h1>Your Information</h1>
				<table>
					<tr>
						<td><?php echo $form->label(__('first_name', true)); ?></td>
						<td class="field"><?php echo $form->input('first_name', array('value' => $currentUser['User']['first_name'], 'label' => false)); ?></td>
					</tr>
					<tr>
						<td><?php echo $form->label(__('last_name', true)); ?></td>
						<td class="field"><?php echo $form->input('last_name', array('value' => $currentUser['User']['last_name'], 'label' => false)); ?></td>
					</tr>
					<tr>
						<td><?php echo $form->label(__('password', true)); ?></td>
						<td class="field"><?php echo $form->input('user_password', array('label' => false, 'type' => 'password')); ?></td>
					</tr>
					<tr>
						<td><?php echo $form->label(__('password_again', true)); ?></td>
						<td class="field"><?php echo $form->input('user_password_again', array('label' => false, 'type' => 'password')); ?></td>
					</tr>
					<tr>
						<td><?php echo $form->label(__('sex', true)); ?></td>
						<td class="field"><?php echo $form->input('sex', array('options' => array('male' => __('male', true), 'female' => __('female', true)), 'default' => $currentUser['Profile']['sex'], 'label' => false)); ?></td>
					</tr>
					<tr>
						<td><?php echo $form->label(__('sex_interest', true)); ?></td>
						<td class="field"><?php echo $form->input('sex_interest', array('options' => array('men' => __('men', true), 'women' => __('women', true), 'bisexual' => __('bisexual', true)), 'default' => $currentUser['Profile']['sex_interest'], 'label' => false)); ?></td>
					</tr>
					<tr>
						<td><?php echo $form->label(__('political', true)); ?></td>
						<td class="field"><?php echo $form->input('political', array('value' => $currentUser['Profile']['political'], 'label' => false)); ?></td>
					</tr>
					<tr>
						<td><?php echo $form->label(__('religious', true)); ?></td>
						<td class="field"><?php echo $form->input('religious', array('value' => $currentUser['Profile']['religious'], 'label' => false)); ?></td>
					</tr>
					<tr>
						<td><?php echo $form->label(__('rel_status', true)); ?></td>
						<td class="field"><?php echo $form->input('rel_status', array('options' => array('single' => __('single', true), 'in_a_relationship' => __('in_a_relationship', true), 'married' => __('married', true), 'its_complicated' => __('its_complicated', true)), 'default' => $currentUser['Profile']['rel_status'], 'label' => false)); ?></td>
					</tr>
					<tr>
						<td><?php echo $form->label(__('city', true)); ?></td>
						<td class="field"><?php echo $form->input('city', array('value' => $currentUser['Profile']['city'], 'label' => false)); ?></td>
					</tr>
					<tr>
						<td><?php echo $form->label(__('country', true)); ?></td>
						<td class="field"><?php echo $form->input('country_id', array('options' => $countries, 'default' => $currentUser['Profile']['country_id'], 'label' => false)); ?></td>
					</tr>
					<tr>
						<td><?php echo $form->label(__('dob', true)); ?></td>
						<td class="field"><?php echo $form->input('dob', array('dateFormat' => 'MDY', 'minYear' => date('Y') - 97, 'maxYear' => date('Y') + 2, 'selected' => strtotime($currentUser['Profile']['dob']), 'label' => false)); ?></td>
					</tr>
				</table>
				<?php echo $form->end(__('update_profile', true)); ?>
			</div>
		</div>
		<div class="options_group clearfix">
			<h1>Gallery</h1>
			<?php echo $this->element('settings/gallery_options'); ?>
		</div>
		<div class="options_group clearfix">
			<h1>Delete Account</h1>
			<?php echo $html->link(__('delete_account', true), array('slug' => $currentUser['User']['slug'], 'controller' => 'settings', 'action' => 'delete'), null, __('delete_are_you_sure', true)); ?>
		</div>
	</div>
</div>
