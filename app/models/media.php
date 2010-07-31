<?php
class Media extends AppModel {
	var $name = 'Media';
	var $hasOne = array(
		'User' => array(
			'ClassName' => 'User',
			'foreignKey' => 'avatar_id',
		),
	);
}