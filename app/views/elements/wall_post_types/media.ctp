<?php
$url = array(
	'slug' => $post['PostAuthor']['slug'],
	'controller' => 'albums',
	'action' => 'view',
	$post['Media']['id'],
);
	$iUrl = array('controller' => 'media', 'action' => 'news', $post['Media']['id']);
	echo $markdown->parse(
		$author_name . __('added_media', true) . "\r\n" .
		$html->link($post['Media']['title'], $url) . '<br />' .
		$htmlImage->image($iUrl, array('url' => $url)) //, array('/url' => $aUrl,));
	);
