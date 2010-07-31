	<div id="profile_summary">
		<h1 class="name">
<?php	
			echo ucwords($userMeta['first_name']) . ' ' . ucwords($userMeta['last_name']);
?>
		</h1>
<?php
		echo $form->create('UserMeta', array('url' => '/e/' . $currentUser['User']['slug']));
?>
		<table>
<?php
			foreach($userMeta as $name => $value):
?>
					<tr class="{$name}">
						<td class="value">
<?php
							echo $form->input($name, array('value' => $value));
?>
						</td>
					</tr>
<?php
			endforeach;
?>
		</table>
<?php
		echo $form->end('Save');
?>
	</div>