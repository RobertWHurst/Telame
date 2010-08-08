<div class="search">
	<?php 
		echo $form->create('search', array(
			'url' => $html->url('/users/search', true)
		));
		echo $form->input('query', array('label' => __('search', true)));
		echo $form->submit(__('search', true));
		echo $form->end();
	?>
</div>