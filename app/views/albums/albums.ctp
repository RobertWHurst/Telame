<div id="content" class="clearfix">
	<div id="wrap_main_sidebar">
		<div id="logo">
			<?php echo $html->image('users/logo.png', array('title' => __('site_name', true),'url' => array('controller' => 'pages', 'action' => 'signup'))); ?>
		</div>
		<?php echo $this->element('main_sidebar'); ?>
	</div>
	<div id="page">
		<div id="page_head" class="clearfix">
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