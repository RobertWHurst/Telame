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
			<table>
				<tr>
<?php			foreach($allAlbums as $a) {
					echo '<td>';
					$link = '/albums/' . $slug . '/' . $a['Album']['title'];
					echo $html->link(
							$html->image('/i/t/' . $a['Cover']['id']), 
							$link,
							array('escape' => false)
						);
					echo $html->link($a['Album']['title'], $link);
					echo '</td>';
				}
?>
				</tr>
			</table>
		</div>
	</div>
</div>
<?php echo $this->element('copyright'); ?>