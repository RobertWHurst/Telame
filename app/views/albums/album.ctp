<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'gallery',
	'summary',
	'tall_header',
	'main_sidebar',
	'users/profile',
	'albums/albums'
));
$this->set('script_for_layout', array(
	'jquery',
	'base',
	'main_sidebar'
));
?>
<div id="albums" class="clearfix">
	<?php foreach($album as $media): ?>
		<div class="media">
			<div class="wrap_single">
				<div class="single">
<?php
					$aUrl = array('controller' => 'albums', 'action' => 'album', $user['User']['slug'], $media['Media']['id']);
					$iUrl = array('controller' => 'media', 'action' => 'single', $media['Media']['id']);
					echo $html->image($iUrl, array('url' => $aUrl,));
?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>