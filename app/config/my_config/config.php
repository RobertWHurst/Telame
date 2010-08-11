<?php
// Specify custome config information here
Configure::write('SiteName', 'Telame');

// Image sizes
Configure::write('AvatarSize', array('height' => 60, 'width' => 60));
Configure::write('ThumbSize', array('height' => 100, 'width' => 100));
Configure::write('ProfileSize', array('height' => 300, 'width' => 480));

// User information
Configure::write('UserMeta', array('first_name', 'last_name', 'gallery_mode'));
Configure::write('UserInfo', array(
						'wall_posts' => array(
							'limit' => 10,
							'offset' => 0
						),
						'friends' =>  array(
							'limit' => 12,
							'offset' => 0,
							'order' => 'random()'
						),
					)
				);


?>