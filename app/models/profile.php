<?php
class Profile extends AppModel {
	var $name = 'Profile';
	var $hasMany = array('ProfileMeta' => array(
			'dependent' => true
		)
	);
	var $helpers = array('ProfileSummary');
	
	function afterFind($results){
		
		foreach($results as $key => $row){
						
			//extract the metadata so we can correct it's structure
			$metadata = $row['ProfileMeta'];
			
			//remove the old metadata structure
			unset($results[$key]['ProfileMeta']);
			
			//fix the structure
			foreach($metadata as $meta_set){			
				$results[$key]['ProfileMeta'][$meta_set['meta_key']] = $meta_set['meta_value'];
			}
		}
		
		return $results;
	}
}