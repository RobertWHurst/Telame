<?php
if(is_array($user['UserMeta'])):
	//make a pesudo array of labels
	$fields = array(
		'location' => __('Location', true),
		'born' => __('Born', true),
		'sex' => __('Sex', true),
		'group' => __('TelaGroup', true),
		'joined' => __('Joined', true),
		'interested_in' => __('Interested In', true)
	);
?>
	<div id="profile_summary">
		<h1 class="name">
<?php	
			echo ucwords($user['UserMeta']['first_name']['value']) . ' ' . ucwords($user['UserMeta']['last_name']['value']);
?>
		</h1>
		<table>
<?php
			foreach($fields as $key => $label):
				if(isset($user['UserMeta'][$key]) && $value = __($user['UserMeta'][$key]['value'], true)):
?>
					<tr class="{$key}">
						<th class="key">
<?php
							echo $label . ':';
?>
						</th>
						<td class="value">
<?php
							echo $value
?>
						</td>
					</tr>
<?php
				endif;
			endforeach;
?>
		</table>
	</div>
<?php
endif;
?>