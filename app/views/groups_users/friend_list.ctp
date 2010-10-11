<?php
//INCLUDES
$html->css(array(
	'base',
	'tall_header',
	'main_sidebar',
	'wall_posts',
	'user_list',
	'users/search'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
	'main_sidebar'
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
$this->set('title_for_layout', __('friend_list', true));

$this->Paginator->options(array(
	'url' => array(
		'controller' => 'groups_users',
		'action' => 'friendList',
		'slug' => $this->params['slug'],
	)
));
?>
<div id="page_head">
	<h1 class="page_title"><?php echo __('friend_list_title', true); ?></h1>
</div>
<div id="page_body" class="clearfix">
	<div class="page_controls clearfix">
		<?php echo $this->element('paginator'); ?>
	</div>
	<div id="search_results">
<?php
		foreach($friends as $friend) {

			//generate the url array to the user
			$url = array('controller' => 'users', 'action' => 'profile', $friend['Friend']['slug']);
?>
			<div class="user clearfix">
				<div class="avatar">
<?php				$image_url = array('controller' => 'media', 'action' => 'avatar', $friend['Friend']['avatar_id']);
					echo $html->image($image_url, array_merge(array('url' => $url), Configure::read('AvatarSize')));
?>
				</div>
				<div class="controls">
					[controls]
				</div>
				<div class="name"><?php echo $html->link($friend['Friend']['full_name'], $url); ?></div>

				<div class="summary">
					<?php if(!is_null($friend['Friend']['Profile']['sex'])): ?>
						<div class="item sex">
							<p><?php __('sex'); ?>: <?php echo __($friend['Friend']['Profile']['sex'], true); ?></p>
						</div>
					<?php endif; ?>

					<?php if(!is_null($friend['Friend']['Profile']['city'])): ?>
					<div class="item city">
						<p><?php __('lives_in'); ?>: <?php echo $friend['Friend']['Profile']['city']; ?></p>
					</div>
					<?php endif; ?>

					<?php if(!is_null($friend['Friend']['Profile']['dob'])): ?>
					<div class="item dob">
						<p><?php __('dob'); ?>:
<?php						$dob = strtotime($friend['Friend']['Profile']['dob']);
							echo date('M j, Y', $dob);
?>
						</p>
					</div>
					<?php endif; ?>
				</div>
			</div>
		<?php } ?>
	</div>

	<div class="page_controls clearfix">
		<?php echo $this->element('paginator'); ?>
	</div>
</div>
