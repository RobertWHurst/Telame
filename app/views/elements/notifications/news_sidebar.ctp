<div id="news_sidebar">
	<div class="news_filters">
		<h1>Filter Updates</h1>
		<ul>
			<?php foreach($friendLists as $filter): ?>
				<li class="<?php echo ($filter['selected'])? 'filter current' : 'filter'; ?>"><?php echo $html->link($filter['FriendList']['name'], array('controller' => 'notifications', 'action' => 'news', $filter['FriendList']['id'])); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>