<?php
class User extends AppModel {
	var $name = 'User';
	var $hasMany = array('ProfileMeta' => array(
			'dependent' => true
		), 'WallPost'
	);
	var $helpers = array('ProfileSummary');
	
	function afterFind($results){
		
		if(is_array($results)){
			foreach($results as $key => $row){
						
				if(isset($row['ProfileMeta']) && is_array($row['ProfileMeta'])){
					//extract the metadata so we can correct it's structure
					$metadata = $row['ProfileMeta'];
					
					//remove the old metadata structure
					unset($results[$key]['ProfileMeta']);
					
					//fix the structure
					foreach($metadata as $meta_set)		
						$results[$key]['ProfileMeta'][$meta_set['meta_key']] = $meta_set['meta_value'];
					
				}
			}
		}
		
		return $results;
	}
}