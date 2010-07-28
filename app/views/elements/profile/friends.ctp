<h2>Friends</h2>
<?php
foreach ($user['Friend'] as $friend) {
	echo $html->image('/users/avatar/' . $friend['User']['id'], array('width' => '60', 'height' => '60'));
	echo $html->link($friend['User']['slug'], '/p/' . $friend['User']['slug']);
	echo '<br />';
}

?>