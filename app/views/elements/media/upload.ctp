<div class="upload_media">

	<!-- borders -->
	<div class="box top"><div class="box top right"></div><div class="box top left"></div></div>
	<div class="box bottom"><div class="box bottom right"></div><div class="box bottom left"></div></div>
	<div class="box right"></div><div class="box left"></div>
	<!-- end of borders -->

	<div class="box inner">
<?php
		echo $form->create('Media', array('type' => 'file', 'url' => array('slug' => $currentUser['User']['slug'], 'controller' => 'media', 'action' => 'upload')));
			echo $form->input('file', array('type' => 'file'));
			echo $form->input('title');
			echo $form->input('album', array('options' => $albums, 'selected' => $aid));
		echo $form->end(__('upload', true));
?>
	</div>
</div>