<?php
$this->set('title_for_layout', __('the_brewery', true));

?>
<div id="page_head" class="clearfix">
	<h1 class="page_title"><?php echo __('the_brewery', true); ?></h1>
</div>
<div id="page_body" class="clearfix">
	<div id="introduction">
		<p></p>
	</div>
	<div id="comments">
<?php
		foreach ($projects as $project) {
			pr($project);
		}
?>
	</div>
</div>