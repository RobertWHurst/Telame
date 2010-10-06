<?php
$url = array(
	'slug' => $post['PostAuthor']['slug'],
	'controller' => 'albums',
	'action' => 'view',
	$post['Media']['id'],
);
	$iUrl = array('slug' => $post['PostAuthor']['slug'], 'controller' => 'media', 'action' => 'preview', $post['Media']['id']);
	echo $markdown->parse(
		$author_name . __('added_media', true) . "\r\n" .
		$html->link($post['Media']['title'], $url) . '<br />' .
		$htmlImage->image($iUrl, array('url' => $url)) //, array('/url' => $aUrl,));
	);
