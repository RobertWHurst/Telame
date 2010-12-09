<div id="news_sidebar">
	<div class="news_filters">
		<h1><?php __('filter_title'); ?></h1>
		<ul>
			<?php foreach($friendLists as $filter): ?>
				<li class="filter <?php echo ($filter['Group']['id'] == $selectedFriendList) ? ' current' : ''; ?>">
					<?php echo $html->link($filter['Group']['title'], array(
						'controller' => 'news', 
						'action' => 'news', 
						$filter['Group']['id']
					)); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>

<?php if(!empty($birthdays)): ?>
	<div class="news_filters">
		<h1><?php __('birthdays_today'); ?></h1>
<?php 	foreach ($birthdays as $bday) {
			echo $htmlImage->image('icons/cake.png', array('static' => true));
			echo $html->link($bday['User']['full_name'], array(
				'controller' => 'users',
				'action' => 'profile',
				$bday['User']['slug'],
			));
			$year = date('Y', strtotime($bday['Profile']['dob']));
			$thisYear = date('Y');
			echo ' - ' . ($thisYear - $year);
		}
?>
	</div>
<?php endif; ?>

</div>