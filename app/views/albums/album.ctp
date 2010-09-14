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
			$this->params['pass'][1]
		),
		'classes' => 'button'
	)
));
?>
<div id="albums" class="clearfix">
<?php foreach($album as $media): ?>
		<div class="media">
			<div class="wrap_single">
				<div class="single">
<?php
					//$aUrl = array('controller' => 'albums', 'action' => 'album', $user['User']['slug'], $media['Media']['id']);
					$iUrl = array('controller' => 'media', 'action' => 'single', $media['Media']['id']);
					echo $html->image($iUrl);//, array('/url' => $aUrl,));
?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>