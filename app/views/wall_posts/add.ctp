<?php
foreach($wallPosts as $wallPost)
	echo $this->element('users/wall_post', array('post' => $wallPost));