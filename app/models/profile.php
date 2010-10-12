<?php
class Profile extends AppModel {
	public $belongsTo = array(
		'Country',
		'User',
		'SO' => array(
			'className' => 'User',
			'foreignKey' => 'rel_status_user_id'
		),
	);
}