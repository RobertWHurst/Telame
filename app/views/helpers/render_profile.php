<?php
class RenderProfileHelper extends AppHelper {

	var $helpers = array('Form', 'Html', 'Time');

    function summary($user) {

    	if(is_array($user['UserMeta'])){

    		//make a pesudo array of labels
    		$fields = array(
    			'location' => __('Location', true),
    			'born' => __('Born', true),
    			'sex' => __('Sex', true),
    			'group' => __('TelaGroup', true),
    			'joined' => __('Joined', true),
    			'interested_in' => __('Interested In', true)
    		);

			//open the div
   			$output = '<div id="profile_summary">';

   			//add the profile name
   			$output .= "<h1 class=\"name\">{$user['UserMeta']['first_name']} {$user['UserMeta']['last_name']}</h1>";

   			//open the table
   			$output .= '<table>';

   			// pukes out meta data in summary
   			foreach($fields as $key => $label){
				if(isset($user['UserMeta'][$key]) && $value = __($user['UserMeta'][$key], true)){

					//if special formatting is needed add it within an if statement below this comment

					//create the output
					$output .= "<tr class=\"{$key}\"><th class=\"key\">{$label}:</th><td class=\"value\">{$value}</td></tr>";
				}
			}

			//close the table
   			$output .= '</table>';

			//close the table
   			$output .= '</div>';

   			echo $output;
   		}
   	}

    function edit($user) {

    	if(is_array($user['UserMeta'])){

    		//make a pesudo array of labels
    		$fields = array(
    			'location' => __('Location', true),
    			'born' => __('Born', true),
    			'sex' => __('Sex', true),
    			'group' => __('TelaGroup', true),
    			'joined' => __('Joined', true),
    			'interested_in' => __('Interested In', true)
    		);

			//open the div
   			$output = '<div id="profile_summary">';

   			//add the profile name
   			$output .= "<h1 class=\"name\">{$user['UserMeta']['first_name']} {$user['UserMeta']['last_name']}</h1>";

   			//open the table
   			$output .= $this->Form->create('User', array('action' => '/edit/' . $user['User']['slug']));
   			$output .= '<table>';

   			// pukes out meta data in summary
   			foreach($fields as $key => $label){
				if(isset($user['UserMeta'][$key]) && $value = __($user['UserMeta'][$key], true)){

					//if special formatting is needed add it within an if statement below this comment

					//create the output
					$output .= "<tr class=\"{$key}\"><th class=\"key\">{$label}:</th>
						<td class=\"value\">";
					$output .= $this->Form->input($label, array('value' => $value, 'label' => false));
					$output .= "</td></tr>";
				}
			}

			//close the table
   			$output .= '</table>';
			$output .= $this->Form->end('Save');

			//close the table
   			$output .= '</div>';

   			echo $output;
   		}
   	}


   	function gallery($user){

	   	//open the profile gallery div
	   	$output = '<div id="profile_gallery">';

	   	//add a div of the image frame
	   	$output .= '<div class="frame"></div>';

	   	//open the images div
	   	$output .= '<div class="images">';

	   	//get the gallery dispaly mode (single == 1 or multi == 2)
	   	switch($user['UserMeta']['gallery_mode']){

	   		//if just one image is set to load
	   		case 1:

	   			//TODO: this logic is incorrect. the line below must depend on the system eric is creating for images. For now use the mockup code

	   			/* MOCK CODE */
	   			$output .= $this->Html->image('/a/' . $user['User']['id'], array('title' => $user['UserMeta']['first_name'] . ' ' .  $user['UserMeta']['last_name']));
	   			/* END OF MOCK CODE */

	   			break;

	   		case 2:

	   			//TODO when the use has more than one image then loop through them here
	   			break;

	   	}

	   	//close the images div and the gallery div.
	   	$output .= '</div>';
	   	$output .= '</div>';

	   	echo $output;
   	}
   	
   	function wall($user){
   	
   		//begin the wall div and the input div
		$output = '<div id="profile_wall"><div id="profile_wall_input">';
		
		//create the form
		$output .= $this->Form->create('WallPost', array('url' => '/wall_posts/add/'));
		$output .= $this->Form->input('post', array('label' => __('What\'s on your mind?', true), 'type' => 'text'));
		$output .= $this->Form->hidden('user_id', array('value' => $user['User']['id']));
		$output .= $this->Form->end(__('Post', true));
		
		//close the input div and open the posts div
		$output .= '</div><div id="profile_wall_posts">';
		
		//if there are posts on the wall then loop through them
		if (!empty($user['WallPost']) && is_array($user['WallPost'])) {
			foreach ($user['WallPost'] as $post) {
			
				//open the post div and the author info div
				$output .= '<div class="wall_post"><div class="author_info">';
				
				//add the author's avatar
				//TODO: erics uncommited avatar code here...
				$output .= '<div class="avatar">[avatar]</div>';
				
				//echo the author's name as a link
				//TODO: this should not be a slug.
				$output .= '<div class="name">' . $this->Html->link($post['PostAuthor']['slug'], '/' . $post['PostAuthor']['slug']) . ' says:</div>';
				
				//close author info and open the post content div				
				$output .= '</div><div class="post_content">';
				
				//the content	
				$output .= "<p>{$post['post']}</p>";
				
				//close the content div				
				$output .= '</div>';
				
				//the delete button
				$output .= '<div class="delete">' . $this->Html->image('icons/delete.png', array('title' => __('Delete',true), 'url' => '/wall_posts/delete/' . $post['id'])) . '</div>';
				
				//the timestamp				
				$output .= '<div class="time">' . $this->Time->timeAgoInWords($post['posted']) . '</div>';
				
				//close the post div				
				$output .= '</div>';
			}
		}
		else {
		
			$output .= '<p class="no_posts">' . __('You don\' have any posts; what gives?', true) . '</p>';
		}

		//close the posts div and the wall div			
		$output .= '</div></div>';
		
		echo $output;
   	}
   	
   	function friends($user){
   		//set the limit
   		$limit = 9;
   		
   		//is it random?
   		$random = true;
   		
   		//get the users friends
   		$friends = $user['Friend'];  
   		
   		//open the friends div
   		$output = '<div class="friends">';
   		  
   		//the title
   		$output .= '<h1>Friends</h1>';  		
   		
   		//if the user has friends loop through them
   		if(is_array($friends)){
   			foreach($friends as $friend){
   			
   				//get the avatar url
   				$avatar_url = $this->Html->url(array('controller' => 'users', 'action' => 'avatar', $friend['User']['id']));
   				
   				//get the user's profile url
   				$profile_url = $this->Html->url(array('controller' => 'users', 'action' => 'profile', $friend['User']['slug']));
   				
   				//get the friend's avatar and name to insert into a link
   				$link_content = $this->Html->image($avatar_url, array('width' => '60', 'height' => '60'));
   				$link_content .= '[first name]';
   				
   				$output .= $this->Html->link($link_content, $profile_url, array('escape' => false));
   			}   		
   		}
   		else{
   			$output .= '[TelaMeet plug here...]';
   		}
   		
   		//close the friends div
   		$output .= '</div>';
   		
   		echo $output;
   	}
}