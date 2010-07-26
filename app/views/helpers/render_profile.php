<?php
class RenderProfileHelper extends AppHelper {
    function summary($profile_object) {
    
    	//make a pesudo array of labels
    	$fields = array(
    		'location' => __('Location'),
    		'sex' => __('sex'),
    		'born' => __('born'),
    		'joined' => __('joined')
    	);
    
		//open the div
   		$output = '<div id="profile_summary">';
   		
   		//add the profile name
   		$output .= "<h1 class=\"name\">{$profile_object['name']}</h1>";
   		
   		//open the table
   		$output .= '<table>';
   		
   		foreach($fields as $key => $label){
			if(isset($profile_object[$key]) && $value = __($profile_object[$key])){
		
				//if special formatting is needed add it within an if statement below this comment
			
				//create the output
				$output .= "<tr class=\"{$key}\"><th class=\"key\">{$label}:</th><td class=\"value\">{$value}</td></tr>";
			}
		}
	
		//close the table
   		$output .= '</table>'; 
	
		//close the table
   		$output .= '</div>';
   	}
}