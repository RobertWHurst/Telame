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
	<!-- Commented out until styled properly -- <div id="module_notifications" class="module">
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
		$link_content = $this->HtmlImage->Image('icons/asterisk_yellow.png', array('static' => true, 'alt' => '', 'id' => 'notifications')) . __('notifications', true) . $noteCount;
		echo $html->link($link_content, array('slug' => $currentUser['User']['slug'], 'controller' => 'notifications', 'action' => 'index'), array('escape' => false));
?>
		</div>
	</div> -->
	<div id="module_newsfeed" class="module">
		<div class="link">
<?php
		$link_content = $this->HtmlImage->Image('icons/newspaper.png', array('static' => true, 'alt' => '')) . __('news_feed', true);
		echo $html->link($link_content, array('controller' => 'pages', 'action' => 'news'), array('escape' => false));
?>
		</div>
	</div>
	<div id="module_profile" class="module">
		<div class="link">
<?php
		$link_content = $this->HtmlImage->Image('icons/user.png', array('static' => true, 'alt' => '')) . __('profile', true);
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
		$link_content = $this->HtmlImage->Image('icons/email.png', array('static' => true, 'alt' => '')) . __('messages', true) . $msgCount;
		echo $html->link($link_content, array('slug' => $currentUser['User']['slug'], 'controller' => 'messages', 'action' => 'inbox'), array('escape' => false));
?>
		</div>
	</div>
	<div id="module_lists" class="module">
		<div class="link">
<?php
		$link_content = $this->HtmlImage->Image('icons/group.png', array('static' => true, 'alt' => '')) . __('friends', true);
		echo $html->link($link_content, array('slug' => $currentUser['User']['slug'], 'controller' => 'groups_users', 'action' => 'friendList'), array('escape' => false));
?>
		</div>
	</div>
	<div id="module_photos" class="module">
		<div class="link">
<?php
		$link_content = $this->HtmlImage->Image('icons/photo.png', array('static' => true, 'alt' => '')) . __('media', true);
		echo $html->link($link_content, array('slug' => $currentUser['User']['slug'], 'controller' => 'albums', 'action' => 'albums'), array('escape' => false));
?>
		</div>
	</div>
	<div id="module_calendar" class="module">
		<div class="link">
<?php
		$link_content = $this->HtmlImage->Image('icons/date.png', array('static' => true, 'alt' => '')) . __('calendar', true);
		echo $html->link($link_content, array('slug' => $currentUser['User']['slug'], 'controller' => 'events', 'action' => 'calendar'), array('escape' => false));
?>
		</div>
	</div>
	<div id="module_brewery" class="module">
		<div class="link">
<?php
		$link_content = $this->HtmlImage->Image('icons/date.png', array('static' => true, 'alt' => '')) . __('brewery', true);
		echo $html->link($link_content, 'http://brewery.telame.com', array('escape' => false));
?>
		</div>
	</div>
</div>