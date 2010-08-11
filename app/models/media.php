<?php
class Media extends AppModel {
	var $belongsTo = array('Album');
	var $hasOne = array(
		'User' => array(
			'ClassName' => 'User',
			'foreignKey' => 'avatar_id',
		),
	);

// -------------------- Custom functions

}