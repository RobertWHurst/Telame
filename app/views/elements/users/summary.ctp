<div id="profile_summary">
	<h1 class="name">
		<?php echo $user['User']['UserProfile']['full_name']; ?>
	</h1>
	<table>
<?php	 foreach($user['User']['UserProfile'] as $key => $value): ?>
			<tr class="<?php echo $key; ?>">
				<th class="key">
					<?php echo Inflector::humanize($key); ?>
				</th>
				<td class="value">
					<?php echo $value; ?>
<?php
					//complex logic is represended between line breaks
?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>	
</div>