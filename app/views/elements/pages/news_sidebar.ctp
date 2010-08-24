<div id="news_sidebar">
	<div class="news_filters">
		<h1><?php __('filter_title'); ?></h1>
		<ul>
			<?php foreach($friendLists as $filter): ?>
				<li class="<?php echo ($filter['selected'])? 'filter current' : 'filter'; ?>"><?php echo $html->link($filter['Group']['title'], array('controller' => 'pages', 'action' => 'news', $filter['Group']['id'])); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>