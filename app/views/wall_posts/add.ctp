<?php
foreach($wallPosts as $wallPost)
	echo $this->element('wallPost', array('post' => $wallPost, 'show_post_controls' => true));