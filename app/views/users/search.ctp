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

$this->Paginator->options(array(
	'url' => array(
		'controller' => 'users',
		'action' => 'search',
		'query' => $this->params['query'],
	)
));
$this->set('title_for_layout', __('search', true));

?>
<div id="page_head">
	<h1 class="page_title"><?php echo __('search_title', true); ?></h1>
</div>
<div id="page_body" class="clearfix">
	<div class="page_controls clearfix">
		<?php echo $this->element('paginator'); ?>
	</div>
	<div id="search_results">
<?php	foreach($results as $user):

		//generate the url array to the user
		$url = array('controller' => 'users', 'action' => 'profile', $user['User']['slug']);
?>
			<div class="user clearfix">
				<div class="avatar">
<?php				$image_url = array('controller' => 'media', 'action' => 'avatar', $user['User']['avatar_id']);
					echo $html->image($image_url, array_merge(array('url' => $url), Configure::read('AvatarSize')));
?>
				</div>
				<div class="controls">
					<div class="add_friend">
<?php					if($currentUser['User']['id'] == $user['User']['id']) {
							echo '<p>' . __('is_you', true) . '</p>';
						} elseif(!$user['Friend']['is_friend']) {
							if($user['Friend']['request_sent']) {
								echo '<p>' . __('request_sent', true) . '</p>';
							} else {
								echo $html->link(__('add_as_friend', true), array('slug' => $currentUser['User']['slug'], 'controller' => 'groups_users', 'action' => 'addFriend', $user['User']['id']));
							}
						} elseif($user['Friend']['list']) {
							echo '<p title="' . __('added_to_list_', true) . $user['Friend']['list'] . '">' . __('is_friend', true) . '</p>';
						} ?>
					</div>
				</div>
				<div class="name"><?php echo $html->link($user['User']['full_name'], $url); ?></div>

				<div class="summary">
					<?php if(!is_null($user['Profile']['sex'])): ?>
						<div class="item sex">
							<p><?php __('sex'); ?>: <?php echo __($user['Profile']['sex'], true); ?></p>
						</div>
					<?php endif; ?>

					<?php if(!is_null($user['Profile']['city'])): ?>
					<div class="item city">
						<p><?php __('lives_in'); ?>: <?php echo $user['Profile']['city']; ?></p>
					</div>
					<?php endif; ?>

					<?php if(!is_null($user['Profile']['country_id'])): ?>
					<div class="item country">
						<p><?php __('country'); ?>: <?php echo $user['Profile']['Country']['name'] . ' ' . $htmlImage->image('flags' . DS . strtolower($user['Profile']['Country']['iso2']) . '.png', array('static' => true)); ?></p>
					</div>
					<?php endif; ?>

					<?php if(!is_null($user['Profile']['dob'])): ?>
					<div class="item dob">
						<p><?php __('dob'); ?>:
<?php						$dob = strtotime($user['Profile']['dob']);
							echo date('M j, Y', $dob);
?>
						</p>
					</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="page_controls clearfix">
		<?php echo $this->element('paginator'); ?>
	</div>

</div>