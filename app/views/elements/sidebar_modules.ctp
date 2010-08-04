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
		$link_content = $this->Html->image('icons/email.png') . 'Inbox';
		echo $html->link($link_content, '#', array('escape' => false));
?>
		</div>
	</div>
	<div id="module_newsfeed" class="module">
		<div class="link">
<?php 
		$link_content = $this->Html->image('icons/newspaper.png') . 'News Feed';
		echo $html->link($link_content, array('controller' => 'notifications', 'action' => 'news'), array('escape' => false));
?>
		</div>
	</div>
	<div id="module_lists" class="module">
		<div class="link">
<?php 
		$link_content = $this->Html->image('icons/group.png') . 'Friend Lists';
		echo $html->link($link_content, '#', array('escape' => false));
?>
		</div>
	</div>
</div>