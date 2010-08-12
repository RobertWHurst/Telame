<div class="friends">
   	<h1><?php echo ($user['User']['id'] == $currentUser['User']['id'] ? 'Your' : ucfirst($user['Profile']['first_name'] . '\'s')); ?> Friends</h1>
   	<div class="thumbs">
<?php
		if(is_array($friends)):
			foreach($friends as $friend):
?>
				<div class="thumb">
<?php
					//get the avatar url
					$avatar_url = $html->url(array('controller' => 'media', 'action' => 'avatar', $friend['Friend']['avatar_id']));

					//get the user's profile url
					$profile_url = $html->url(array('controller' => 'users', 'action' => 'profile', $friend['Friend']['slug']));

					//get the friend's avatar and name to insert into a link
					$link_content = '<div class="avatar">' . $html->image($avatar_url) . '</div>';
					$link_content .= $friend['Friend']['Profile']['full_name'];
		
					//echo the thumb link
					echo $html->link($link_content, $profile_url, array('escape' => false));
?>
				</div>
<?php
			endforeach;
		else:
?>
	[TelaMeet advert plug here...]
<?php
   	endif;
?>
	</div>
</div>