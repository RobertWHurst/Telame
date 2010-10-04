<?php
class Profile extends AppModel {
	var $belongsTo = array(
		'Country',
		'User',
		'SO' => array(
			'className' => 'User',
			'foreignKey' => 'rel_status_user_id'
		),
	);
}