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
$this->set('title_for_layout', __('site_name', true) . ' | ' . $user['User']['full_name'] . '\'s ' . __('settings', true));
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
			$profile = $currentUser['Profile'];
			$user =  $currentUser['User'];

			echo $form->create('Profile', array('url' => array('controller' => 'settings', 'action' => 'basic')));
?>
			<div class="options_group clearfix">
				<h1>Your Information</h1>
<?php
				echo $form->input('first_name', array(
					'label' => __('first_name', true),
					'value' => $user['first_name']
				));
				echo $form->input('last_name', array(
					'label' => __('last_name', true),
					'value' => $user['last_name']
				));
				echo $form->input('sex', array(
					'label' => __('sex', true),
					'default' => $profile['sex'],
					'options' => array(
						'male' => __('male', true),
						'female' => __('female', true)
					)
				));
				echo $form->input('sex_interest', array(
					'label' => __('sex_interest', true),
					'default' => $profile['sex_interest'],
					'options' => array(
						'men' => __('men', true),
						'women' => __('women', true),
						'bisexual' => __('bisexual', true)
					)
				));
				echo $form->input('political', array(
					'label' => __('political', true),
					'value' => $profile['political']
				));
				echo $form->input('religious', array(
					'label' => __('religious', true),
					'value' => $profile['religious']
				));
				echo $form->input('rel_status', array(
					'label' => __('rel_status', true),
					'default' => $profile['rel_status'],
					'options' => array(
						'male' => __('single', true),
						'female' => __('dating', true),
						'married' => __('married', true)
					)
				));
				echo $form->input('country_id', array(
					'label' => __('country', true),
					'options' => $countries,
					'default' => $profile['country_id'],
				));
				echo $form->input('dob', array(
					'dateFormat' => 'Y-M-D',
					'minYear' => date('Y') - 97,
					'maxYear' => date('Y') + 2,
				));
?>
				<?php echo $form->end(__('update_profile', true)); ?>
			</div>
		</div>
		<div class="options_group clearfix">
			<h1>Gallery</h1>
			<?php echo $this->element('settings/gallery_options'); ?>
		</div>
	</div>
	<?php pr($currentUser); ?>
</div>
