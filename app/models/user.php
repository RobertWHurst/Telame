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
    
   	function afterFind($results) {
   		if (isset($results['id']) && isset($results['email'])) {
   			$this->id = $results['id'];
   			$results['first_name'] = ucwords($this->getMeta('first_name'));
   			$results['last_name'] = ucwords($this->getMeta('last_name'));
   			$results['full_name'] = ucwords($this->getMeta('first_name') . ' ' . $this->getMeta('last_name'));
   		}
		return $results;
	}

	function beforeFind($queryData) {
		if (isset($queryData['conditions']['User.email'])) {
			$queryData['conditions']['User.email'] = strtolower($queryData['conditions']['User.email']);
		}
		return $queryData;
	}
	
	function getProfile($slug) {	
		//get the profile
		$this->recursive = 2;
		$this->Behaviors->attach('Containable');
		$user = $this->find('first', array(
			'conditions' => array(
				'lower(slug)' => strtolower($slug)
			),
			'contain' => array(
				'Friend' => array(
					'limit' => 10, // 10 friends
					'order' => 'random()', // keep 'em random
					'User' // they belong to the 'User' model
				),
				'Media',
				'WallPost' => array(
					'PostAuthor'
				)
			)
		));
		return $user;
	}
}