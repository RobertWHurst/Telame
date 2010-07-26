<?php
class RenderProfileHelper extends AppHelper {
    function summary($profile_object) {
    	
    	if(is_array($profile_object['ProfileMeta'])){
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
   			$output .= "<h1 class=\"name\">{$profile_object['ProfileMeta']['first_name']} {$profile_object['ProfileMeta']['last_name']}</h1>";
   			
   			//open the table
   			$output .= '<table>';
   			
   			foreach($fields as $key => $label){
				if(isset($profile_object['ProfileMeta'][$key]) && $value = __($profile_object['ProfileMeta'][$key], true)){
			
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