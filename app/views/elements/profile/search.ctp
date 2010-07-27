<div class="search">
	<?php 
		echo $form->create('search', array(
			'url' => $html->url('/profiles/search', true)
		));
		echo $form->input('query', array('label' => __('Search', true)));
		echo $form->submit(__('Search', true));
		echo $form->end();
	?>
</div>