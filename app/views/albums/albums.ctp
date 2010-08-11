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
		</div>
	</div>
</div>
<?php echo $this->element('copyright'); ?>