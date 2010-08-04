<div id="profile_summary">
	<h1 class="name">
		<?php echo $user['User']['UserProfile']['full_name']; ?>
	</h1>
<?php echo $form->create('UserMeta', array('url' => '/e/' . $user['User']['slug'])); ?>
	<table>
<?php	 foreach($user['User']['UserProfile'] as $key => $value): ?>
			<tr class="<?php echo $key; ?>">
				<td class="value">
					<?php echo $form->input($key, array('label' => Inflector::humanize($key), 'value' => $value));
					//complex logic is represended between line breaks
?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php echo $form->end('Save'); ?>
</div>