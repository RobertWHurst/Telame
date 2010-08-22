<?php
//if there are posts on the wall then loop through them
if (!empty($wallPosts) && is_array($wallPosts)){
	foreach ($wallPosts as $post)
		echo $this->element('wallPost', array('post' => $post, 'show_post_controls' => true));
}