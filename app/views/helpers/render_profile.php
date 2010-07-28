<?php
class RenderProfileHelper extends AppHelper {
    function summary() {
    	
    	if(is_array($user['UserMeta'])){
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
   			$output .= "<h1 class=\"name\">{$user['UserMeta']['first_name']} {$user['UserMeta']['last_name']}</h1>";
   			
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
   	
   	function gallery(){
	   	
	   	//open the profile gallery div
	   	$output = '<div id="profile_gallery">';
	   	
	   	//add a div of the image frame
	   	$output .= '<div class="frame"></div>';
	   	
	   	//open the images div
	   	$output .= '<div class="images">';
	   	
	   	//get the gallery dispaly mode (single or multi)
	   	switch($user['UserMeta']['gallery_mode']){
	   	
	   		//if just one image is set to load
	   		case 'single':
	   			
	   			//TODO: this logic is incorrect. the line below must depend on the system eric is creating for images. For now use the mockup code
	   			
	   			/* MOCK CODE */
	   			$output .= $html->image(strtolower($user['User']['slug']) . '.png', array('title' => $user['UserMeta']['first_name'] . ' ' .  $user['UserMeta']['last_name']));
	   			/* END OF MOCK CODE */
	   		
	   			break;
	   			
	   		case 'multi':
	   			
	   			//TODO when the use has more than one image then loop through them here
	   			break;
	   			
	   	}
	   	
	   	//close the images div and the gallery div.
	   	$output .= '</div>';
	   	$output .= '</div>';
	   	
	   	return $output;
   	}
}