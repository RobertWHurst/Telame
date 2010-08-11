<div id="content" class="clearfix">
	<div id="wrap_main_sidebar">
		<div id="logo">
			<!-- TODO: link to the news feed -->
			<?php echo $html->image('logo.png', array('title' => __('site_name', true),'url' => array('controller' => 'pages', 'action' => 'signup'))); ?>
		</div>
		<?php echo $this->element('main_sidebar'); ?>
	</div>
	<div id="page">
		<div id="page_head" class="clearfix">
			<div id="profile_summary">
				<h1 class="name">
					<?php echo $this->data['Profile']['full_name']; ?>
				</h1>
<?php			echo $form->create('Profile', array('url' => '/e/' . $currentUser['User']['slug']));
				echo $form->input('first_name');
				echo $form->input('last_name');
				echo $form->radio('sex', array('Male' => 'Male', 'Female' => 'Female'));
				echo $form->hidden('id', array('value' => $this->data['Profile']['id']));
				echo $form->end('Save');
?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element('copyright'); ?>