<?php
Configure::write('BlacklistUsernames', array(
	'c', //confirm new user
	'login',
	'logout',
	'search',
	'signup',
	'Tom Zalgo Aldis',
));

// Specify custome config information here
Configure::write('SiteName', 'Telame');
Configure::write('StaticDomain', 'http://s.telame.com');

// Email stuff
Configure::write('EmailInfo', array(
	'from' => 'Telame.com <admin@telame.com>',
	
));

// Image sizes
Configure::write('AvatarSize', array('height' => 60, 'width' => 60));
Configure::write('CommentSize', array('height' => 30, 'width' => 30));
Configure::write('LargeSize', array('height' => 800, 'width' => 600));
Configure::write('PreviewSize', array('height' => 200, 'width' => 200));
Configure::write('ProfileSize', array('height' => 300, 'width' => 480));
Configure::write('SingleSize', array('height' => 235, 'width' => 235));
Configure::write('ThumbSize', array('height' => 167, 'width' => 167));

Configure::write('AllowedFileTypes', array(
    'image/jpeg',
    'image/gif',
    'image/png',
    'image/pjpeg',
    'image/x-png'
  )); 

// User information

Configure::write('UserAcls', array(
	'calendar',
	'friends',
	'media' => array(
		'documents', 
		'images', 
		'videos'
	),
	'profile',
));

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

define('USER_DIR', APP . 'users' . DS);