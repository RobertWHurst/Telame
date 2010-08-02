<div id="profile_summary">
	<h1 class="name">
		<?php echo $user['User']['full_name']; ?>
	</h1>
	<table>
<?php	 foreach(Configure::read('UserMeta') as $meta): ?>
			<tr class="<?php echo $meta; ?>">
				<th class="key">
					<?php echo Inflector::humanize($meta); ?>
				</th>
				<td class="value">
					<?php echo $user['User'][$meta]; ?>
<?php
					//complex logic is represended between line breaks
?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>	
</div>