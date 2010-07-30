<?php
	//set the limit
   	$limit = 9;
   	
   	//is it random?
   	$random = true;
   		
   	//get the users friends
   	$friends = $user['Friend'];
?>
   		
<div class="friends">
   	<h1>Friends</h1>
<?php
	if(is_array($friends)):
	
		//make an itorator
		$i = 0;
		
		//randomize the array
		shuffle($friends);
		
   		foreach($friends as $friend):
   		
   			//make sure we haven't hit the limit
   			if($i == $limit)
   				break;
   			
   			//get the avatar url
   			$avatar_url = $html->url(array('controller' => 'users', 'action' => 'avatar', $friend['User']['id']));
   			
   			//get the user's profile url
   			$profile_url = $html->url(array('controller' => 'users', 'action' => 'profile', $friend['User']['slug']));
   			
   			//get the friend's avatar and name to insert into a link
   			$link_content = $html->image($avatar_url, array('width' => '60', 'height' => '60'));
   			$link_content .= '[first name]';
   			
   			//echo the thumb link
   			echo $html->link($link_content, $profile_url, array('escape' => false));
   			
   			//push the itorator
   			$i += 1;
   			
   		endforeach;
   	else:
?>
		[TelaMeet advert plug here...]
<?php
   	endif;
?>
</div>