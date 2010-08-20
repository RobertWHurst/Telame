<?php
App::import('Sanitize');
$this->set('documentData', array(
		'xmlns:dc' => 'http://purl.org/dc/elements/1.1/'));

$this->set('channelData', array(
		'title' => __('site_name', true) . ' | Wall Posts',
		'link' => $html->url('/', true),
		'description' => __('site_name', true) . ' | Wall Posts',
		'language' => 'en-us'));

foreach ($wallPosts as $post) {
		$postTime = strtotime($post['WallPost']['posted']);
 
		$postLink = array(
			'controller' => 'entries',
			'action' => 'view',
			'year' => date('Y', $postTime),
			'month' => date('m', $postTime),
			'day' => date('d', $postTime));
		// This is the part where we clean the body text for output as the description 
		// of the rss item, this needs to have only text to make sure the feed validates
		$bodyText = preg_replace('=\(.*?\)=is', '', $post['WallPost']['post']);
		$bodyText = $text->stripLinks($bodyText);
		$bodyText = Sanitize::stripAll($bodyText);
		$bodyText = $text->truncate($bodyText, 400, '...', true, true);

		$author = $html->link($post['PostAuthor']['Profile']['full_name'], 'http://' . env('SERVER_NAME') . '/' . $post['PostAuthor']['slug']);

		echo  $rss->item(array(), array(
			'title' => $author . ': ' . $post['WallPost']['post'],
			'link' => $postLink,
			'guid' => array('url' => $postLink, 'isPermaLink' => 'true'),
			'description' =>  $author . ': ' . $bodyText,
			'dc:creator' => $author,
			'pubDate' => date("D, j M Y H:i:s O", strtotime($post['WallPost']['posted']))
			)
		);
	}//Sat, 07 Sep 2002 00:00:01 GMT


?>