<?php
class Friend extends AppModel{
	var $name = 'Friend';
	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'parent_user_id'
	));

	function afterFind($results) {
		foreach($results as $key => $val) {
			if(isset($val['User'])) {
				$this->User->id = $results[$key]['User']['id'];
				$results[$key]['User']['first_name'] = ucwords($this->User->getMeta('first_name'));
				$results[$key]['User']['last_name'] = ucwords($this->User->getMeta('last_name'));
			}
		}
		return $results;
	}

}