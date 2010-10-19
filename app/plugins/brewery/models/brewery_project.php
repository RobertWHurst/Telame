<?php
class BreweryProject extends BreweryAppModel {
	var $name = 'BreweryProject';

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $hasMany = array(
		'BreweryComment',
	);
}