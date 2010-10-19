<?php
class BreweryComment extends BreweryAppModel {
	var $name = 'BreweryComment';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Project' => array(
			'className' => 'Brewery.BreweryProject',
			'foreignKey' => 'project_id',
		)
	);
}
?>