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
	'header_glow',
	'main_sidebar'
));
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
					echo $html->image($iUrl, array('/url' => $aUrl,));
?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>