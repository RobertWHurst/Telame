<div class="search">
	<?php 
		echo $form->create('search', array(
			'url' => $html->url('/profiles/search', true)
		));
		echo $form->input('query', array('label' => __('Search')));
		echo $form->submit(__('Search'));
		echo $form->end();
	?>
</div>