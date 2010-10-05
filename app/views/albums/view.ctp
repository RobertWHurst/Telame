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
	$current = array_search($media['Media']['id'], $mediaList);

	$count = count($mediaList);

	$prev = ($current == 0 ? $mediaList[$count-1] : $mediaList[$current - 1]);
	$next = ($current == ($count-1) ? $mediaList[0] : $mediaList[$current + 1]);


	echo $html->link(__('image_prev', true), array('slug' => $user['User']['slug'], 'controller' => 'albums', 'action' => 'view', $prev));

	echo $html->link(__('image_next', true), array('slug' => $user['User']['slug'], 'controller' => 'albums', 'action' => 'view', $next));

	$iUrl = array('controller' => 'media', 'action' => 'large', $media['Media']['id']);
	echo $htmlImage->image($iUrl);
?>
</div>