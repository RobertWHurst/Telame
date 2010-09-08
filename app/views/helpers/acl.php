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
		foreach($acoTree as $aco): ?>
			<div class="aco-object">
				<h1>
<?php				echo __('permissions_for', true);
					echo ' ';
					echo __($aco['Aco']['alias'], true);
?>
				</h1>
<?php 			foreach($aco['Groups'] as $group):
					$canRead = $group['Group']['canRead'];
?>
					<div class="aco-group clearfix <?php echo ($canRead) ? 'on' : 'off'; ?>">
						<div class="decription">
							<p>
<?php							echo '<strong>' . $group['Group']['title'] . '</strong> ';
								echo $canRead ? __('can_read', true) . ' ' . __($aco['Aco']['alias'] ,true) : __('cannot_read', true) . ' ' . __($aco['Aco']['alias'], true); 
?>
							</p>
						</div>
						<div class="switch">
<?php						echo $this->Form->input((!is_null($parent) ? $parent . '.' : '') . $aco['Aco']['alias'] . '.' . $group['Group']['title'], array(
								'type' => 'checkbox',
								'checked' => ($canRead ? 'checked' : ''),
								'label' => __('public', true)
								//'id' => "Aco_{$group['Group']['id']}"
							));
?>
						</div>
					</div>
<?php 			endforeach;
?>
			</div>
<?php	if (isset($aco['Children'])) {
			$this->displayAcoTree($aco['Children'], $aco['Aco']['alias']);
		}
		endforeach;
	}
}
?>