<?php

class AclHelper extends AppHelper {

	var $helpers = array('Html');

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
	
}
?>