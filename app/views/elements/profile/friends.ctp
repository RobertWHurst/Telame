<h2>Friends</h2>
<?php
// get the name of who's page your on, check if it's your own or not
$name = (Configure::read('UID') == $user['User']['id'] ? 'You have ' : $user['User']['slug'] . ' has ');
// count number of friends
$count = count($user['Friend']);

// spew it out
echo $name . $count . ($count === 1 ? ' friend' : ' friends');
echo '<br />';

foreach ($user['Friend'] as $friend) {
	echo $html->image('/a/' . $friend['User']['id'], array('width' => '60', 'height' => '60'));
	echo $html->link($friend['User']['slug'], '/' . $friend['User']['slug']);
	echo '<br />';
}

?>