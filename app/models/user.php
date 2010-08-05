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

   	function afterFind($results) {
   		// This is FUCKING UGLY, and WILL NEED fixing, however, it works in the mean time
		// I am NOT prould of this code :( it is however functional, but not very adaptable 
		// Possibley use Set::extract and set::combine to fix this
		if (is_array($results)) {
			foreach ($results as $key => $result) {
				if (is_array($result)) {
					foreach ($result as $key2 => $model) {
						if (isset($model['id']) && isset($model['email'])) {
							$results[$key][$key2]['UserProfile'] = $this->getMetaData($model['id'], 'profile');
							if ($key2 == 'User') {
								$results[$key][$key2]['UserSettings'] = $this->getMetaData($model['id'], 'settings');
							}
						}
					}
				}
			}
		}
		if (isset($results['id']) && isset($results['email'])) {
			$results['UserProfile'] = $this->getMetaData($results['id'], 'profile');
		}
		return $results;
	}

	function beforeFind($queryData) {
		if (isset($queryData['conditions']['User.email'])) {
			$queryData['conditions']['User.email'] = strtolower($queryData['conditions']['User.email']);
		}
		return $queryData;
	}

	function getIdFromSlug($slug) {
		$this->recursive = -1;
		$user = $this->findBySlug($slug);
		return $user['User']['id'];
	}

	function getMetaData($id, $type='profile') {
		$this->id = $id;
		$results = $this->getMeta('User.' . $type);
		if (strtolower($type) == 'profile' && isset($results['first_name']) && isset($results['last_name'])) {
			$results['full_name'] = ucwords($results['first_name'] . ' ' . $results['last_name']);
		}
		return $results;
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
					'User'
				),
				'Media',
			)
		));
		return $user;
	}
}