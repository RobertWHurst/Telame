<?php 
//make a navigation strip
$links = array(
	array(
		'label' => __('inbox', true),
		'url' => array(
			'slug' => $currentUser['User']['slug'],
			'controller' => 'messages',
			'action' => 'inbox'
		),
		'classes' => 'button'
	),
	array(
		'label' => __('compose_message', true),
		'url' => array(
			'slug' => $currentUser['User']['slug'],
			'controller' => 'messages',
			'action' => 'compose'
		),
		'classes' => 'button'
	),
	array(
		'label' => __('messages_sent', true),
		'url' => array(
			'slug' => $currentUser['User']['slug'],
			'controller' => 'messages',
			'action' => 'sent'
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
	if($this->params['controller'] == $link['url']['controller'] && $this->params['action'] == $link['url']['action'])
		$link['classes'] = 'button current';
?>				
	<li><?php echo $html->link($link['label'], $link['url'], array('class' => $link['classes'])); ?></li>
<?php endforeach; ?>
</ul>