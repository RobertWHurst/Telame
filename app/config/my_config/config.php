<?php
// Specify custome config information here
Configure::write('SiteName', 'Telame');

// Image sizes
Configure::write('AvatarSize', array('height' => 60, 'width' => 60));
Configure::write('ThumbSize', array('height' => 167, 'width' => 167));
Configure::write('PreviewSize', array('height' => 200, 'width' => 200));
Configure::write('SingleSize', array('height' => 235, 'width' => 235));
Configure::write('ProfileSize', array('height' => 300, 'width' => 480));

Configure::write('AllowedFileTypes', array(
    'image/jpeg',
    'image/gif',
    'image/png',
    'image/pjpeg',
    'image/x-png'
  )); 

// User information
Configure::write('UserInfo', array(
	'wall_posts' => array(
		'limit' => 10,
		'offset' => 0
	),
	'friends' =>  array(
		'limit' => 9,
		'offset' => 0,
		'order' => 'random()'
	),
));