<div class="search">
	<?php 
		echo $form->create('Search', array('url' => $html->url('/search', true),));
		echo $form->input('query', array('label' => __('search', true)));
		echo $form->submit(__('search', true));
		echo $form->end();
	?>
</div>