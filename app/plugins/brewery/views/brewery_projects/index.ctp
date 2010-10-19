<?php
$this->set('title_for_layout', __('brewery_comments', true));

?>
<div id="page_head" class="clearfix">
	<h1 class="page_title"><?php echo __('brewery_comments', true); ?></h1>
</div>
<div id="page_navigation" class="clearfix">

</div>
<div id="page_body" class="clearfix">
	<div id="comments">
<?php	foreach ($projects as $project) {
			pr($project);
		}
?>	</div>
</div>