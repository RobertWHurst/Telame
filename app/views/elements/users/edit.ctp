<div id="profile_summary">
	<h1 class="name">
		<?php echo $user['User']['full_name']; ?>
	</h1>
<?php echo $form->create('UserMeta', array('url' => '/e/' . $user['User']['slug'])); ?>
	<table>
<?php	 foreach(Configure::read('UserMeta') as $meta): ?>
			<tr class="<?php echo $meta; ?>">
				<td class="value">
					<?php echo $form->input($meta, array('label' => Inflector::humanize($meta), 'value' => $user['User'][$meta]));
					//complex logic is represended between line breaks
?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>	
<?php echo $form->end('Save'); ?>
</div>