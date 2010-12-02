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
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
$this->set('title_for_layout', __('album', true));

$this->set('nav_links', array(
	array(
		'label' => __('album', true),
		'url' => array(
			'slug' => $user['User']['slug'],
			'controller' => 'albums',
			'action' => 'album',
			$this->params['pass'][0],
		),
		'classes' => 'button'
	)
)); ?>
<div id="albums" class="clearfix">
<?php
	if ($user['User']['id'] == $currentUser['User']['id']) {
		echo $html->link(__('upload_images', true), array('slug' => $currentUser['User']['slug'], 'controller' => 'media', 'action' => 'upload', $aid));
	}
	foreach($album as $media): ?>
		<div class="media">
<?php		echo $media['Media']['title']; 
			if ($user == $currentUser) {
				echo $html->link(__('album_set_cover', true), array('slug' => $currentUser['User']['slug'], 'controller' => 'albums', 'action' => 'setAlbumCover', $media['Media']['model_id'], $media['Media']['id']));
				echo $html->link(__('delete_image', true), array('slug' => $currentUser['User']['slug'], 'controller' => 'media', 'action' => 'delete', $media['Media']['id']), null, __('media_delete_confirm', true));
			}
?>
			<div class="wrap_single">
				<div class="single">
<?php				$aUrl = array('slug' => $user['User']['slug'], 'controller' => 'albums', 'action' => 'view', $media['Media']['id']);
					$iUrl = array('controller' => 'media', 'action' => 'single', $media['Media']['id']);
					echo $htmlImage->image($iUrl, array('url' => $aUrl,));
?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>