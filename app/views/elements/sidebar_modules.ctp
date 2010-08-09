<div id="modules">
<?php 
	/**
	 * 
	 * THIS IS THE SIDEBAR MODULE ELEMENT
	 * 
	 * TODO:
	 * This file should dynamically load modules based on a users settings.
	 * The options should be fead from the app controller.
	 * 
	 */
	 
	/* DELETE THE BELOW BEFORE RELEASE!!! */
?>
	<div id="module_inbox" class="module">
		<div class="link">
<?php 
		$link_content = $this->Html->image('icons/email.png') . __('inbox', true);
		echo $html->link($link_content, '#', array('escape' => false));
?>
		</div>
	</div>
	<div id="module_newsfeed" class="module">
		<div class="link">
<?php 
		$link_content = $this->Html->image('icons/newspaper.png') . __('news_feed', true);
		echo $html->link($link_content, array('controller' => 'notifications', 'action' => 'news'), array('escape' => false));
?>
		</div>
	</div>
	<div id="module_lists" class="module">
		<div class="link">
<?php 
		$link_content = $this->Html->image('icons/group.png') . __('friend_list', true);
		echo $html->link($link_content, array('controller' => 'friends', 'action' => 'listFriends'), array('escape' => false));
?>
		</div>
	</div>
	<div id="module_photos" class="module">
		<div class="link">
<?php 
		$link_content = $this->Html->image('icons/photo.png') . __('albums', true);
		echo $html->link($link_content, array('controller' => 'albums', 'action' => 'albums'), array('escape' => false));
?>
		</div>
	</div>
</div>