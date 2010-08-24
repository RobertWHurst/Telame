<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'gallery',
	'summary',
	'tall_header',
	'main_sidebar',
	'users/profile',
	'users/wall',
	'users/wall_sidebar'
));
$this->set('script_for_layout', array(
	'jquery',
	'base',
	'main_sidebar',
	'users/wall_input',
	'users/wall'
));
?>
<div id="content" class="clearfix">
	<div id="wrap_main_sidebar">
		<div id="logo">
			<!-- TODO: link to the news feed -->
			<?php echo $html->image('logo.png', array('title' => __('site_name', true),'url' => array('controller' => 'pages', 'action' => 'news'))); ?>
		</div>
		<?php echo $this->element('main_sidebar'); ?>
	</div>
	<div id="page">
		<div id="page_head" class="clearfix">
<?php
			//render the profile gallery
			echo $this->element('gallery');

			//render the profile summary
			echo $this->element('summary');
?>
		</div>
		<div id="page_body" class="clearfix">
<?php			echo $form->create('Media', array('type' => 'file'));
				echo $form->input('file', array('type' => 'file'));
				echo $form->end(__('upload', true));
			
?>		</div>
	</div>
</div>
<?php echo $this->element('copyright'); ?>