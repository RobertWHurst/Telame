<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'gallery',
	'summary',
	'tall_header',
	'main_sidebar',
	'albums/albums'
));
$this->set('script_for_layout', array(
	'jquery',
	'base',
	'header_glow',
	'main_sidebar',
	'albums/thumbs'
));
?>
<div id="albums" class="clearfix">
<?php
	echo $html->link(__('create_new_album', true), array('controller' => 'albums', 'action' => 'new'));
	foreach($albums as $album): ?>
		<div class="album">
			<div class="wrap_thumb">
				<div class="thumb">
<?php
					$aUrl = array('controller' => 'albums', 'action' => 'album', $user['User']['slug'], $album['Album']['slug']);
					$iUrl = array('controller' => 'media', 'action' => 'thumb', $album['Cover']['id']);
					echo $html->image($iUrl, array('url' => $aUrl,));
?>
				</div>
				<div class="image">
<?php
					$iUrl = array('controller' => 'media', 'action' => 'preview', $album['Cover']['id']);
					echo $html->image($iUrl, array('url' => $aUrl,));
?>
				</div>
			</div>
			<div class="label">
				<?php echo $html->link($album['Album']['title'], $aUrl); ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>