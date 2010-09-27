<?php
//INCLUDES
$html->css(array(
	'base',
	'gallery',
	'summary',
	'tall_header',
	'main_sidebar',
	'albums/albums'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
	'profile',
	'main_sidebar',
	'albums/thumbs',
	'albums/create_album'
);
foreach ($js as $j) {
	$javascript->link($j, false);
}

?>
<div id="albums" class="clearfix">
<?php
	foreach($albums as $album): ?>
		<div class="album">
			<div class="wrap_thumb">
				<div class="thumb">
<?php
					$aUrl = array('slug' => $user['User']['slug'], 'controller' => 'albums', 'action' => 'album', $album['Album']['slug']);
					$iUrl = array('slug' => $user['User']['slug'], 'controller' => 'media', 'action' => 'thumb', $album['Cover']['id']);
					echo $html->image($iUrl, array('url' => $aUrl,));
?>
				</div>
				<div class="image">
<?php
					$iUrl = array('slug' => $user['User']['slug'], 'controller' => 'media', 'action' => 'preview', $album['Cover']['id']);
					echo $html->image($iUrl, array('url' => $aUrl,));
?>
				</div>
			</div>
			<div class="label">
				<?php echo $html->link($album['Album']['title'], $aUrl, array('title' => $album['Album']['title'])); ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
<div id="album_controls" class="clearfix">
	<?php echo $html->link(__('create_album', true), array('slug' => $currentUser['User']['slug'], 'controller' => 'albums', 'action' => 'newAlbum'), array('class' => 'create_album')); ?>
	<?php echo $html->link(__('delete_albums', true), '#', array('class' => 'js', 'style' => 'display:none;')); ?>
</div>