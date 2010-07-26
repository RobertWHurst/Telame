<?php
class Profile extends AppModel {
	var $name = 'Profile';
	var $hasMany = array('ProfileMeta' => array(
			'dependent' => true
		)
	);
}