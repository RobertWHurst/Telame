<?php
foreach ($user['Friend'] as $friend) {
	echo $friend['User']['slug'];
	echo '<br />';
}

?>