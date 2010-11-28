<?php
//INCLUDES
$html->css(array(
	'base',
	'simple_header'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
	'users/taglines'
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
$this->set('title_for_layout', __('home', true));
?>
<div id="content">

	<?php echo $this->element('pages/taglines'); ?>

	<div id="noSignup">
		<h1>We're not ready to sign you up just yet</h1>
		<p>Sorry, we know you might be excited but we need to finish painting the walls and adding the furniture first.</p>
		<p>If you want to know when we're up and ready to go, signup for our <?php echo $html->link('newsletter', 'http://automailer.thinktankdesign.ca/signup/for_list/telame'); ?>.
	</div>

	<footer>
			<?php echo $this->element('copyright'); ?>
		</footer>

</div>