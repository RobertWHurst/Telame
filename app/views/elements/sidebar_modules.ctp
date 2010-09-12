<div id="modules">
<?php
	/**
	 *
	 * THIS IS THE SIDEBAR MODULE ELEMENT
	 *
	 * TODO:
	 * This file should dynamically load modules based on a users settings.
	 * The options should be fed from the app controller.
	 *
	 */

	/* DELETE THE BELOW BEFORE RELEASE!!! */
?>
	<div id="module_notifications" class="module">
<?php
		$noteCount = ($currentUser['User']['notification_count'] ? ' (' . $currentUser['User']['notification_count'] . ')' : '');
		if ($noteCount) {
			echo $html->scriptStart(); ?>
			window.onload = function() {
				var xTimer = window.setInterval("changeImg()",1000);
			}
<?php		echo $html->scriptEnd();
		}
?>
		<div class="<?php echo ($noteCount)? 'link active' : 'link'; ?>">
<?php
		$link_content = $this->Html->image('icons/asterisk_yellow.png', array('alt' => __('notifications', true), 'id' => 'notifications')) . __('notifications', true) . $noteCount;
		echo $html->link($link_content, array('controller' => 'notifications', 'action' => 'index'), array('escape' => false));
?>
		</div>
	</div>
	<div id="module_newsfeed" class="module">
		<div class="link">
<?php
		$link_content = $this->Html->image('icons/newspaper.png', array('alt' => __('news_feed', true))) . __('news_feed', true);
		echo $html->link($link_content, array('controller' => 'pages', 'action' => 'news'), array('escape' => false));
?>
		</div>
	</div>
	<div id="module_wall" class="module">
		<div class="link">
<?php
		$link_content = $this->Html->image('icons/comment.png', array('alt' => __('wall', true))) . __('wall', true);
		echo $html->link($link_content, array('controller' => 'users', 'action' => 'profile', $currentUser['User']['slug']), array('escape' => false));
?>
		</div>
	</div>
	<div id="module_inbox" class="module">
<?php
		$msgCount = ($currentUser['User']['message_count'] ? ' (' . $currentUser['User']['message_count'] . ')' : '');
?>
		<div class="<?php echo ($msgCount)? 'link active' : 'link'; ?>">
<?php
		$link_content = $this->Html->image('icons/email.png', array('alt' => __('messages', true))) . __('messages', true) . $msgCount;
		echo $html->link($link_content, array('controller' => 'messages', 'action' => 'inbox'), array('escape' => false));
?>
		</div>
	</div>
	<div id="module_lists" class="module">
		<div class="link">
<?php
		$link_content = $this->Html->image('icons/group.png', array('alt' => __('friends', true))) . __('friends', true);
		echo $html->link($link_content, array('controller' => 'groups_users', 'action' => 'friendList'), array('escape' => false));
?>
		</div>
	</div>
	<div id="module_photos" class="module">
		<div class="link">
<?php
		$link_content = $this->Html->image('icons/photo.png', array('alt' => __('media', true))) . __('media', true);
		echo $html->link($link_content, array('controller' => 'albums', 'action' => 'albums'), array('escape' => false));
?>
		</div>
	</div>
</div>