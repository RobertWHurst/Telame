<div id="profile_summary">
	<h1 class="name">
		<?php echo $user['User']['full_name']; ?>
	</h1>
	<?php if(isset($isFriend) && !$isFriend) { ?>
	<h2><?php echo $html->link('Add ' . $user['User']['full_name'] . ' as friend', '/f/a/' . $user['User']['id']); ?></h2>
	<?php } ?>
	<table>
		<tr>
			<th>Name:</th>
			<td><?php echo $user['User']['full_name']; ?></td>
		</tr>
<?php	if (!is_null($user['Profile']['sex'])): ?>
		<tr>
			<th>Sex:</th>
			<td><?php echo $user['Profile']['sex']; ?></td>
		</tr>
<?php	endif;
		if (!is_null($user['Profile']['dob'])): 
?>
		<tr>
			<th>Date of Birth:</th>
			<td><?php 
				$dob = strtotime($user['Profile']['dob']);
				echo date('M j, Y', $dob); 
				if ($dob == strtotime('today')) {
					echo ' ' . $html->image('icons/cake.png');
				}
			?>
			</td>
		</tr>
<?php	endif; ?>
	</table>	
</div>