<?php
$hrl->css(array( 'key' => 'wall', 'url' => 'common/wall' ));
?>
<div id="page_head" class="clearfix">
	<h1 class="page_title"><?php echo __('view_post', true); ?></h1>
</div>
<div id="page_body" class="clearfix">
	<div id="wall_posts">
		<?php echo $this->element( 'wall_post', array( 'post' => $post ) ); ?>
	</div>
</div>