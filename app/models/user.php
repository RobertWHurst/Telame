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
			foreach (Configure::read('UserMeta') as $meta) {
				$results[$meta] = ucwords($this->getMeta($meta));
			}
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
	
	function getProfile($slug, $arguments = false){		
		$defaults = Configure::read('UserInfo');
		//parse the options
		$options = $this->parseArguments($defaults, $arguments);
		
		//get the profile
		$this->recursive = 2;
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
				'WallPost' => array(
					'limit' => $options['wall_posts']['limit'],
					'offset' => $options['wall_posts']['offset'],
					'PostAuthor'
				)
			)
		));
		return $user;
	}
}