<?php
$this->set('documentData', array(
		'xmlns:dc' => 'http://purl.org/dc/elements/1.1/'));

	$this->set('channelData', array(
		'title' => __("Most Recent Posts", true),
		'link' => $html->url('/', true),
		'description' => __("Most recent posts.", true),
		'language' => 'en-us'));

foreach ($wallPosts as $post) {
        $postTime = strtotime($post['WallPost']['posted']);
 
        $postLink = array(
            'controller' => 'entries',
            'action' => 'view',
            'year' => date('Y', $postTime),
            'month' => date('m', $postTime),
            'day' => date('d', $postTime));
        // You should import Sanitize
        App::import('Sanitize');
        // This is the part where we clean the body text for output as the description 
        // of the rss item, this needs to have only text to make sure the feed validates
        $bodyText = preg_replace('=\(.*?\)=is', '', $post['WallPost']['post']);
        $bodyText = $text->stripLinks($bodyText);
        $bodyText = Sanitize::stripAll($bodyText);
        $bodyText = $text->truncate($bodyText, 400, '...', true, true);
 
        echo  $rss->item(array(), array(
            'title' => $post['WallPost']['post'],
            'link' => $postLink,
            'guid' => array('url' => $postLink, 'isPermaLink' => 'true'),
            'description' =>  $bodyText,
//            'dc:creator' => $post['WallPost']['author'],
            'pubDate' => $post['WallPost']['created']));
    }


?>