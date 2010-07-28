<?php
class RenderProfileHelper extends AppHelper {
    function summary($profile_object) {
    	
    	if(is_array($profile_object['UserMeta'])){
    		//make a pesudo array of labels
    		$fields = array(
    			'location' => __('Location', true),
    			'born' => __('Born', true),
    			'sex' => __('Sex', true),
    			'group' => __('TelaGroup', true),
    			'joined' => __('Joined', true),
    			'interested_in' => __('Interested In', true)
    		);
    	
			//open the div
   			$output = '<div id="profile_summary">';
   			
   			//add the profile name
   			$output .= "<h1 class=\"name\">{$profile_object['UserMeta']['first_name']} {$profile_object['UserMeta']['last_name']}</h1>";
   			
   			//open the table
   			$output .= '<table>';
   			
   			// pukes out meta data in summary
   			foreach($fields as $key => $label){
				if(isset($profile_object['UserMeta'][$key]) && $value = __($profile_object['UserMeta'][$key], true)){
			
					//if special formatting is needed add it within an if statement below this comment
				
					//create the output
					$output .= "<tr class=\"{$key}\"><th class=\"key\">{$label}:</th><td class=\"value\">{$value}</td></tr>";
				}
			}
		
			//close the table
   			$output .= '</table>'; 
		
			//close the table
   			$output .= '</div>';
   			
   			echo $output;
   		}
   	}
}