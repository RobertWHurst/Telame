<?php

class AclHelper extends AppHelper {

	var $helpers = array('Form', 'Html');

	function formatAcoTree($acoTree) {
		foreach($acoTree as $aco) {
			echo $this->Html->tag('h1', $aco['Aco']['alias']);

			foreach ($aco['Groups'] as $group) {
				echo $this->Html->tag('p', $group['Group']['title'] . ' ' . ($group['Group']['canRead'] ? 'can read' : 'can\'t read'));
			}
			echo '<br />';
			if (isset($aco['Children'])) {
				$this->formatAcoTree($aco['Children']);
			}
		}
	}
	
	function displayAcoTree($acoTree, $parent = null) {
		foreach($acoTree as $aco):
?>
			<div class="aco-object">
				<h1>
<?php				echo __('permissions_for', true);
					echo ' ';
					echo __($aco['Aco']['alias'], true);
?>
				</h1>
				<table>
<?php 			
					foreach($aco['Groups'] as $group):
						$canRead = $group['Group']['canRead'];
?>
						<div class="aco-group clearfix <?php echo ($canRead) ? 'on' : 'off'; ?>">
							<tr>
								<td>
									<?php echo $this->Form->label(__('this_group_can_view_your', true) . ' "' . __($aco['Aco']['alias'], true) . '"'); ?>
								</td>
								<td>
<?php							
									echo $this->Form->input((!is_null($parent) ? $parent . '.' : '') . $aco['Aco']['alias'] . '.Group_' . $group['Group']['id'],
										array(
											'type' => 'radio',
											'default' => $canRead,
											'options' => array(
												__('no', true),
												__('yes', true)
											),
											'legend' => false
										)
									);
?>
								</td>
							</tr>
						</div>
					<?php endforeach; ?>
				</table>
			</div>
<?php
		if (isset($aco['Children'])) {
			$this->displayAcoTree($aco['Children'], $aco['Aco']['alias']);
		}
		endforeach;
	}
}
?>