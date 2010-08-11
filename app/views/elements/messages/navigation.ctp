<?php 
//make a navigation strip
$links = array(
	array(
		'label' => __('messages_inbox_title', true),
		'url' => array(
			'controller' => 'messages',
			'action' => 'inbox'
		),
		'classes' => 'button'
	),
	array(
		'label' => __('messages_sent_title', true),
		'url' => array(
			'controller' => 'messages',
			'action' => 'sent'
		),
		'classes' => 'button'
	),
	array(
		'label' => __('messages_deleted_title', true),
		'url' => array(
			'controller' => 'messages',
			'action' => 'deleted'
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