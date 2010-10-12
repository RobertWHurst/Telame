<div id="profile_summary">
	<div id="profile_controls">
		<div class="add_friend button">
			<?php if(isset($canRequest) && $canRequest) { ?>
				<?php echo $html->link(__('send_friend_request', true), array('slug' => $currentUser['User']['slug'], 'controller' => 'groups_users', 'action' => 'addFriend', $user['User']['id'])); ?>
			<?php } ?>
		</div>
	</div>
	<h1 class="name">
		<?php echo $user['User']['full_name']; ?>
	</h1>
	<table>
		<?php if(!is_null($user['Profile']['sex'])): ?>
			<tr>
				<th>Sex:</th>
				<td><?php echo __($user['Profile']['sex'], true); ?></td>
			</tr>
		<?php endif; ?>
		<?php if(!is_null($user['Profile']['rel_status'])): ?>
			<tr>
				<th>Relationship Status:</th>
				<td><?php echo __($user['Profile']['rel_status'], true);
					if (!empty($user['Profile']['SO'])) {
						echo '<br />' . __('with', true) . ' ' . $html->link($user['Profile']['SO']['full_name'], '/' . $user['Profile']['SO']['slug']);
					}
				?></td>
			</tr>
		<?php endif; ?>
		<?php if(!is_null($user['Profile']['sex_interest'])): ?>
			<tr>
				<th>Interested In:</th>
				<td><?php echo __($user['Profile']['sex_interest'], true); ?></td>
			</tr>
		<?php endif; ?>
		<?php if(!is_null($user['Profile']['city'])): ?>
			<tr>
				<th>Lives In:</th>
				<td><?php echo $user['Profile']['city']; ?></td>
			</tr>
		<?php endif; ?>
		<?php if(!is_null($user['Profile']['country_id'])): ?>
			<tr>
				<th>Country</th>
				<td><?php echo $user['Profile']['Country']['name'] . ' ' . $htmlImage->image('flags' . DS . strtolower($user['Profile']['Country']['iso2']) . '.png', array('static' => true)); ?></td>
			</tr>
		<?php endif; ?>
		<?php if(!is_null($user['Profile']['political'])): ?>
			<tr>
				<th>Political Views:</th>
				<td><?php echo $user['Profile']['political']; ?></td>
			</tr>
		<?php endif; ?>
		<?php if(!is_null($user['Profile']['religious'])): ?>
			<tr>
				<th>Religious beliefs:</th>
				<td><?php echo $user['Profile']['religious']; ?></td>
			</tr>
		<?php endif; ?>
		<?php if(!is_null($user['Profile']['dob'])): ?>
			<tr>
				<th>Date of Birth:</th>
				<td>
				<?php
					$dob = strtotime($user['Profile']['dob']);
					echo date('M j, Y', $dob);
					if ($dob == strtotime('today')) {
						echo ' ' . $html->image('icons/cake.png');
					}
				?>
				</td>
			</tr>
		<?php endif; ?>
	</table>
</div>