<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'simple_header'
));
$this->set('script_for_layout', array(
	'jquery',
	'base',
	'users/taglines'
));
?>
<!- // Consider moving this div to the layout if it's used on all pages.  -->
<div id="content">
	<div id="features">
	
		<h1 class="pageHeading">The Shiny Stuff</h1>
		
		<div class="feature">
			<h1>Feature 1</h1>
			<?php echo $html->image('pages/features/feature_1.png', array('title' => 'Feature 1 img title')); ?>
			<p>Description of Feature 1</p>
		</div>
		
		<footer>
			<?php echo $this->element('copyright'); ?>
		</footer>	
		
	</div>
</div>