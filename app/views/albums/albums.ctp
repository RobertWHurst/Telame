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
<div id="content" class="clearfix">
	<div id="wrap_main_sidebar">
		<div id="logo">
			<!-- TODO: link to the news feed -->
			<?php echo $html->image('logo.png', array('title' => __('site_name', true),'url' => array('controller' => 'notifications', 'action' => 'news'))); ?>
		</div>
		<?php echo $this->element('main_sidebar'); ?>
	</div>
	<div id="page">
		<div id="page_head" class="clearfix">
<?php
			//render the profile gallery
			echo $this->element('gallery');

			//render the profile summary
			echo $this->element('summary');
?>
		</div>
		<div id="page_body" class="clearfix">
			<div id="albums">
				<?php foreach($albums['all'] as $album): ?>
					<div class="album">
						<div class="thumb">
<?php
							$aUrl = array('controller' => 'albums', 'action' => 'albums', $slug, $album['Album']['title']);
							$iUrl = array('controller' => 'media', 'action' => 'thumb', $album['Cover']['id']);
							echo $html->image($iUrl, array('url' => $aUrl,));
?>
						</div>
						<div id="label">
							<?php echo $html->link($album['Album']['title'], $aUrl); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element('copyright'); ?>