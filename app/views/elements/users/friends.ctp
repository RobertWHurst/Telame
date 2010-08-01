<?php
   	//get the users friends
   	$friends = $user['Friend'];
?>

<div class="friends">
   	<h1>Friends</h1>
   	<div class="thumbs">
<?php
		if(is_array($friends)):
			foreach($friends as $friend):
?>
				<div class="thumb">
<?php
					//get the avatar url
					$avatar_url = $html->url(array('controller' => 'media', 'action' => 'avatar', $friend['User']['id']));

					//get the user's profile url
					$profile_url = $html->url(array('controller' => 'users', 'action' => 'profile', $friend['User']['slug']));

					//get the friend's avatar and name to insert into a link
					$link_content = '<div class="avatar">' . $html->image($avatar_url) . '</div>';
					$link_content .= $friend['User']['full_name'];
		
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