<div id="create_album_wrap">
	<div class="create_album">

		<!-- borders -->
		<div class="box top"><div class="box top right"></div><div class="box top left"></div></div>
		<div class="box bottom"><div class="box bottom right"></div><div class="box bottom left"></div></div>
		<div class="box right"></div><div class="box left"></div>
		<!-- end of borders -->

		<div class="box inner">
			<?php echo $form->create('Album', array('url' => array('slug' => $user['User']['slug'], 'controller' => 'albums', 'action' => 'newAlbum'))); ?>
				<div class="titleInput">
					<?php echo $form->input('title'); ?>
				</div>
				<div class="descriptTextarea">
					<?php echo $form->input('description'); ?>
				</div>
			<?php echo $form->end(__('create_album', true)); ?>
		</div>
	</div>
</div>