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

$this->set('nav_links', array(
	array(
		'label' => __('album', true),
		'url' => array(
			'controller' => 'albums',
			'action' => 'album',
			$this->params['pass'][0],
		),
		'classes' => 'button'
	)
)); ?>
<div id="albums" class="clearfix">
<?php
	echo $html->link(__('upload_images', true), array('controller' => 'media', 'action' => 'upload', $aid));
	foreach($album as $media): ?>
		<div class="media">
	<?php		echo $media['Media']['title']; ?>
			<div class="wrap_single">
				<div class="single">
	<?php
					//$aUrl = array('controller' => 'albums', 'action' => 'album', $user['User']['slug'], $media['Media']['id']);
					$iUrl = array('slug' => $user['User']['slug'], 'controller' => 'media', 'action' => 'single', $media['Media']['id']);
					echo $html->image($iUrl);//, array('/url' => $aUrl,));
	?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>