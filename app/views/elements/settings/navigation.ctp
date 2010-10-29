<?php 
//make a navigation strip
$links = array(
	array(
		'label' => __('basic', true),
		'url' => array(
			'slug' => $currentUser['User']['slug'], 
			'controller' => 'settings',
			'action' => 'basic'
		),
		'classes' => 'button'
	),
	array(
		'label' => __('gallery', true),
		'url' => array(
			'slug' => $currentUser['User']['slug'], 
			'controller' => 'settings',
			'action' => 'gallery'
		),
		'classes' => 'button'
	),
	array(
		'label' => __('lists_and_privacy', true),
		'url' => array(
			'slug' => $currentUser['User']['slug'], 
			'controller' => 'settings',
			'action' => 'groups'
		),
		'classes' => 'button'
	),
	array(
		'label' => __('manage_friends', true),
		'url' => array(
			'slug' => $currentUser['User']['slug'], 
			'controller' => 'settings',
			'action' => 'friends'
		),
		'classes' => 'button'
	),
	array(
		'label' => __('services', true),
		'url' => array(
			'slug' => $currentUser['User']['slug'], 
			'controller' => 'services',
			'action' => 'index'
		),
		'classes' => 'button'
	),
);

//allow the view to add the the links
if(isset($nav_links)) {
	$links = array_merge($links, $nav_links);
}
?>
<ul>
<?php
foreach($links as $link):
	if($this->params['controller'] == $link['url']['controller'] && $this->params['action'] == $link['url']['action'])
		$link['classes'] = 'button current';
?>				
	<li><?php echo $html->link($link['label'], $link['url'], array('class' => $link['classes'])); ?></li>
<?php endforeach; ?>
</ul>