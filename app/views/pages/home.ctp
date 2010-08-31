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
<div id="content">

	<?php echo $this->element('pages/taglines'); ?>	
	
	<div id="noSignup">
		<h1>We're not ready to sign you up just yet</h1>
		<p>Sorry, we know you might be excited but we need to finish painting the walls and adding the furniture first.</p>
		<p>If you want to know when were up and ready to go, signup for our <?php echo $html->link('newsletter', 'http://automailer.thinktankdesign.ca/signup/for_list/telame'); ?>.
	</div>
	
	<footer>
			<?php echo $this->element('copyright'); ?>
		</footer>	
	
</div>