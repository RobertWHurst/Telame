<?php
class User extends AppModel {
	var $name = 'User';
	var $belongsTo = array(
		'Media' => array(
			'ClassName' => 'Media',
			'foreignKey' => 'avatar_id',
		)
	);
	
	var $hasMany = array(
		'Friend' => array(
			'ClassName' => 'User',
			'foreignKey' => 'child_user_id'
		),
		'UserMeta' => array(
			'dependent' => true,
			'exclusive' => true
		),
		'WallPost' => array(
			'order' => 	'WallPost.id DESC',
			'dependent' => true,
			'exclusive' => true
		)
	);

	function afterFind($results) {

		if (is_array($results)) {
			foreach ($results as $key => $row) {

				if (isset($row['UserMeta']) && is_array($row['UserMeta'])) {
					//extract the metadata so we can correct it's structure
					$metadata = $row['UserMeta'];

					//remove the old metadata structure
					unset($results[$key]['UserMeta']);

					//fix the structure
					foreach ($metadata as $meta_set) {
						$results[$key]['UserMeta'][$meta_set['meta_key']] = array('value' => $meta_set['meta_value'], 'id' => $meta_set['id']);
					}
				}
			}
		}

		return $results;
	}
	
}
/*
[UserMeta] => Array
        (
            [0] => Array
                (
                    [id] => 9
                    [user_id] => 2
                    [meta_key] => location
                    [meta_value] => Vancouver, Canada
                )

            [1] => Array
                (
                    [id] => 10
                    [user_id] => 2
                    [meta_key] => first_name
                    [meta_value] => Eric
                )

*/