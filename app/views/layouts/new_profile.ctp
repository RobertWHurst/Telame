<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo __('site_name', true) . ' | ' . $title_for_layout; ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $title_for_layout; ?></title>
<?php
	echo $html->meta('favicon.ico', Configure::read('StaticDomain') . '/favicon.ico', array('type' => 'icon'));
	echo $html->meta('viewport', 'width=device-width; initial-scale=1.0; maximum-scale=1.0;');
	echo $html->meta('title', 'Telame');
	echo $html->meta('description', 'Telame: The world\'s first responsible social network');
	echo $html->meta('author', 'Robert Hurst and Eric Friesen');

	$hrl->css(array(
		array( 'key' => 'base', 'url' => 'common/base' ),
		array( 'key' => 'tallHeader', 'url' => 'common/tall_header' ),
		array( 'key' => 'mainSidebar', 'url' => 'common/main_sidebar' ),
		array( 'key' => 'profileGallery', 'url' => 'users/common/profile_gallery' ),
		array( 'key' => 'profileSummary', 'url' => 'users/common/profile_summary' )
	));
	$hrl->js(array(
		array( 'key' => 'jQuery', 'url' => 'common/jquery' ),
		array( 'key' => 'base', 'url' => 'common/base', 'requires' => 'jQuery' ),
		array( 'key' => 'mainSidebar', 'url' => 'common/main_sidebar', 'requires' => array( 'base', 'jQuery' ) )
	));

	$hrl->merge = false;
?>
    <!-- CSS -->
	<?php $hrl->css(); ?>
    <!-- END OF CSS -->

    <!-- SCRIPT -->
	<?php $hrl->js(); ?>
    <!-- END OF SCRIPT -->

</head>
<?php ob_flush(); ?>
<body>
	<?php echo $this->element('flash'); ?>
	<div id="backgroundHead"></div>
	<div id="backgroundBase"></div>
	<div id="wrap_content">
		<div id="content" class="clearfix">
			<div id="wrap_main_sidebar">
				<div id="logo">
					<?php echo $htmlImage->image('logo.png', array('title' => __('site_name', true),'url' => array('controller' => 'news', 'action' => 'news'), 'static' => true)); ?>
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
				<div id="page_navigation" class="clearfix">
					<?php echo $this->element('profile_navigation'); ?>
				</div>
				<div id="page_body" class="clearfix">
					<?php echo $content_for_layout; ?>
				</div>
			</div>
		</div>
		<footer>
			<?php echo $this->element('copyright'); ?>
		</footer>
	</div>
	<?php if(Configure::read('debug') > 0): ?>
		<div id="sql_dump">
			<?php echo $this->element('sql_dump'); ?>
		</div>
	<?php endif; ?>
	<?php echo $this->element('analytics'); ?>
	<div id="modal_screen"></div>
<?php

	if ( Configure::read('debug') > 0 ){
		$hrl->print_log();
	}
?>
</body>
</html>