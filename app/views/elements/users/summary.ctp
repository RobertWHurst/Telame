<div id="profile_summary">
	<h1 class="name"><?php echo $name; ?></h1>
	<table>
<?php	foreach($summary as $key => $meta): ?>
			<tr class="<?php echo $key; ?>">
				<th class="key">
<?php				echo $meta['label']; ?>
				</th>
				<td class="value">
<?php				echo $meta['value']; ?>
				</td>
			</tr>
<?php	endforeach; ?>
	</table>	
</div>