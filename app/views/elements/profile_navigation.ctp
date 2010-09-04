<?php
//make a navigation strip
$links = array(
	array(
		'label' => $user['Profile']['first_name'] . '\'s ' . __('wall', true),
		'url' => array(
			'controller' => 'users',
			'action' => 'profile',
			$user['User']['slug']
		),
		'classes' => 'button'
	),
	array(
		'label' => $user['Profile']['first_name'] . '\'s ' . __('media', true),
		'url' => array(
			'controller' => 'albums',
			'action' => 'albums',
			$user['User']['slug']
		),
		'classes' => 'button'
	),
	array(
		'label' => $user['Profile']['first_name'] . '\'s ' . __('friends', true),
		'url' => array(
			'controller' => 'friends',
			'action' => 'friendList',
			$user['User']['slug']
		),
		'classes' => 'button'
	),
	array(
		'label' => __('about', true) . ' ' . $user['Profile']['first_name'],
		'url' => array(
			'controller' => 'profile',
			'action' => 'about',
			$user['User']['slug']
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