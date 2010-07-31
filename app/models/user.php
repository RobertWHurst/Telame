<?php
class User extends AppModel {
	var $name = 'User';

	var $actsAs = array('Metadata.metadata');
/*	var $actsAs = array('Metadata.metadata' => array(
			'validate' => array(
				'fieldname' => array(
					'rule' => 'postal',
					'message' => 'Must be a postal code'
				)
			)
		)
	);
*/
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
		'WallPost' => array(
			'order' => 	'WallPost.id DESC',
			'dependent' => true,
			'exclusive' => true
		)
	);

	function beforeFind($queryData) {
		if (isset($queryData['conditions']['User.email'])) {
			$queryData['conditions']['User.email'] = strtolower($queryData['conditions']['User.email']);
		}
		return $queryData;
	}
	
}