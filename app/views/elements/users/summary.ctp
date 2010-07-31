	<div id="profile_summary">
		<h1 class="name">
<?php	
			echo ucwords($userMeta['first_name']) . ' ' . ucwords($userMeta['last_name']);
?>
		</h1>
		<table>
<?php
			foreach($userMeta as $name => $value):
?>
				<tr class="{$name}">
					<th class="key">
<?php
						echo Inflector::humanize($name) . ':';
?>
					</th>
					<td class="value">
<?php
						echo $value
?>
					</td>
				</tr>
<?php
			endforeach;
?>
		</table>
	</div>