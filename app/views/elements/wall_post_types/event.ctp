<?php
$url = array(
	'slug' => $post['PostAuthor']['slug'], 
	'controller' => 'events', 
	'action' => 'calendar', 
	substr($post['Event']['start'], 0, 4),
	substr($post['Event']['start'], 5, 2), 
	substr($post['Event']['start'], 8, 2)
);
	echo $markdown->parse(
		$author_name . __('added_an_event', true) . "\r\n<br />" . 
		$html->link($post['Event']['title'], $url)
	);
