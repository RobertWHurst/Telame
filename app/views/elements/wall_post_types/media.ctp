<?php
$url = array(
	'slug' => $post['PostAuthor']['slug'],
	'controller' => 'albums',
	'action' => 'album',
	$post['Media']['album_id'],
);
	echo $markdown->parse(
		$author_name . __('added_media', true) . "\r\n<br />" .
		$html->link($post['Media']['title'], $url)
	);
	$iUrl = array('slug' => $post['PostAuthor']['slug'], 'controller' => 'media', 'action' => 'single', $post['Media']['id']);
	echo $htmlImage->image($iUrl);//, array('/url' => $aUrl,));