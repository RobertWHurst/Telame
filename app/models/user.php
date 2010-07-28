<?php
class User extends AppModel {
	var $name = 'User';
	var $hasMany = array(
		'UserMeta' => array(
			'dependent' => true,
			'exclusive' => true
		),
		'Friend' => array(
			'ClassName' => 'User',
			'foreignKey' => 'child_user_id'			
		),
		'WallPost' => array(
			'order' => 	'WallPost.id DESC',
			'dependent' => true,
			'exclusive' => true
		)
	);
	var $helpers = array('ProfileSummary');
	
	function afterFind($results){
		
		if(is_array($results)){
			foreach($results as $key => $row){
						
				if(isset($row['UserMeta']) && is_array($row['UserMeta'])){
					//extract the metadata so we can correct it's structure
					$metadata = $row['UserMeta'];
					
					//remove the old metadata structure
					unset($results[$key]['UserMeta']);
					
					//fix the structure
					foreach($metadata as $meta_set)		
						$results[$key]['UserMeta'][$meta_set['meta_key']] = $meta_set['meta_value'];
					
				}
			}
		}
		
		return $results;
	}
}