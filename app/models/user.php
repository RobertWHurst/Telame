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
		),
	);

	var $hasMany = array(
		'Album',
		'Friend' => array(
			'ClassName' => 'User',
			'foreignKey' => 'child_user_id'
		),
		'Notification',
		'WallPost' => array(
			'order' => 	'WallPost.id DESC',
			'dependent' => true,
			'exclusive' => true
		)
	);

	var $hasOne = array(
		'Profile'
	);
	
// -------------------- Callback functions

	function beforeFind($queryData) {
		if (isset($queryData['conditions']['User.email'])) {
			$queryData['conditions']['User.email'] = strtolower($queryData['conditions']['User.email']);
		}
		return $queryData;
	}

// --------------------- Custom functions

	function getIdFromSlug($slug) {
		$this->recursive = -1;
		$user = $this->findBySlug($slug);
		return $user['User']['id'];
	}

	function getProfile($slug, $arguments = false){
		$defaults = Configure::read('UserInfo');
		//parse the options
		$options = $this->parseArguments($defaults, $arguments);

		//get the profile
		$this->Behaviors->attach('Containable');

		$user = $this->find('first', array(
			'conditions' => array(
				'lower(slug)' => strtolower($slug)
			),
			'contain' => array(
				'Friend' => array(
					'limit' => $options['friends']['limit'],
					'order' => $options['friends']['order'],
					'User.Profile',
				),
				'Media',
				'Profile',
			)
		));
		return $user;
	}
}