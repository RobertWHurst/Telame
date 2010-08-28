<div id="profile_summary">
	<h1 class="name">
		<?php echo $user['Profile']['full_name']; ?>
	</h1>
	<?php if(isset($isFriend) && !$isFriend) { ?>
	<h2><?php echo $html->link('Add ' . $user['Profile']['full_name'] . ' as friend', '/f/a/' . $user['User']['id']); ?></h2>
	<?php } ?>
	<table>
		<tr class="key">
			<th class="key">Name:</th>
			<td class="value"><?php echo $user['Profile']['full_name']; ?></td>
		</tr>
		<tr class="key">
			<th class="key">Sex:</th>
			<td class="value"><?php echo $user['Profile']['sex']; ?></td>
		</tr>
	</table>	
</div>