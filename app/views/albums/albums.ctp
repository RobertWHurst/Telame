<?php
foreach($allAlbums as $a) {
	echo $html->link($a['Album']['title'], '/albums/' . $slug . '/' . $a['Album']['title']);
}

echo 'All user\'s albums';
pr($allAlbums);

echo 'Current album info';
pr($currentAlbum);
echo 'Media in current album';
pr($albumMedia);

?>