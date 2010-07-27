<div class="search">
	<?php 
		echo $form->create('search', array(
			'url' => $html->url('/profiles/search', true)
		));
		echo $form->input('query', array('label' => 'Search'));
		echo $form->submit('search');
		echo $form->end();
	?>
</div>