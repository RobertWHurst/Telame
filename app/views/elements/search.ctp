<div class="search">
	<?php 
		if(!isset($search_query)){
			$search_query = null;
		}
		
		echo $form->create('Search', array('url' => $html->url('/search', true),));
		echo $form->input('query', array('label' => __('search', true), 'value' => $search_query));
		echo $form->submit(__('search', true));
		echo $form->end();
	?>
</div>