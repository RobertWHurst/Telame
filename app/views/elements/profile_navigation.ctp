<?php
//make a navigation strip
$who = (empty($user['User']['first_name']) ? __('your', true) : $user['User']['first_name']) . '\'s ';
$links = array(
	array(
		'label' => $who . __('wall', true),
		'url' => array(
			'slug' => $user['User']['slug'],
			'controller' => 'users',
			'action' => 'profile',
		),
		'classes' => 'button'
	),
	array(
		'label' => $who . __('media', true),
		'url' => array(
			'slug' => $user['User']['slug'],
			'controller' => 'albums',
			'action' => 'albums',
		),
		'classes' => 'button'
	),
	array(
		'label' => $who . __('calendar', true),
		'url' => array(
			'slug' => $user['User']['slug'],
			'controller' => 'events',
			'action' => 'calendar',
		),
		'classes' => 'button'
	),
	array(
		'label' => $who . __('friends', true),
		'url' => array(
			'slug' => $user['User']['slug'],
			'controller' => 'groups_users',
			'action' => 'friendList',
		),
		'classes' => 'button'
	),
	array(
		'label' => __('about', true) . ' ' . $user['User']['first_name'],
		'url' => array(
			'slug' => $user['User']['slug'],
			'controller' => 'profile',
			'action' => 'about'
		),
		'classes' => 'button'
	)
);

//allow the view to add the the links
if(isset($nav_links))
	$links = array_merge($links, $nav_links);

?>
<ul>
<?php
foreach($links as $link):
	if($this->params['controller'] == $link['url']['controller'] && $this->params['action'] == $link['url']['action']) {
		$link['classes'] = 'button current';
	}
?>
	<li><?php echo $html->link(ucwords(strtolower($link['label'])), $link['url'], array('class' => $link['classes'])); ?></li>
<?php endforeach; ?>
</ul>