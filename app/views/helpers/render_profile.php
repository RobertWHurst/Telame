<?php
/**
 * 
 * THIS IS A DEPRICATED HELPER
 * 
 * Do not add logic to this file.
 * use logic from elsewhere if
 * possible.
 */


class RenderProfileHelper extends AppHelper {

	var $helpers = array('Form', 'Html', 'Time');
    function edit($userMeta) {

		//open the div
			$output = '<div id="profile_summary">';

			//add the profile name
			$output .= "<h1 class=\"name\">{$userMeta['first_name']} {$userMeta['last_name']}</h1>";

			//open the table
			$output .= $this->Form->create('UserMeta', array('url' => array('controller' => 'users', 'action' => 'edit', $user['User']['slug'])));
			$output .= '<table>';

			// pukes out meta data in summary
			foreach($fields as $key => $label){
			if(isset($user['UserMeta'][$key]) && $value = __($user['UserMeta'][$key]['value'], true)){

				//if special formatting is needed add it within an if statement below this comment

				//create the output
				$output .= "<tr class=\"{$key}\"><th class=\"key\">{$label}:</th>
					<td class=\"value\">";
				$output .= $this->Form->input($user['UserMeta'][$key]['id'], array('value' => $value, 'label' => false));
				$output .= "</td></tr>";
			}
		}

		//close the table
			$output .= '</table>';
		$output .= $this->Form->end('Save');

		//close the table
			$output .= '</div>';

			echo $output;
   	}
}