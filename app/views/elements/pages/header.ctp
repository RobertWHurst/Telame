<div id="wrap_header">
	<div id="header">
		<div id="logo">
			<?php echo $html->image('pages/logo.png', array('title' => __('Telame', true), 'url' => array('controller' => 'signup', 'action' => 'index'))); ?>
		</div>
		<div class="headerRight">
			<?php echo $this->element('pages/navigation'); ?>
		</div>
	</div>
</div>