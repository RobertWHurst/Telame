<input type="button" value="Cancel" onclick="back();">
<script type="text/javascript">
    function back() {
        window.location.href ="/<?php echo $currentUser['User']['slug']?>/calendar";
	}
</script><!-- app/views/events/add.ctp -->
<?php 
	echo $form->create('Event', array(
		'target'=> '_parent', 
		'url' => array(
			'slug' => $currentUser['User']['slug'],
			'controller' => 'events',
			'action' => 'add',
		)
	));
	echo $form->input('title' , array('label' => __('event_title', true)));
	echo $form->input('show_on_wall' , array('label' => __('event_show_wall', true), 'default' => 'checked'));
	echo '<br />At: ' . $displayTime;
	echo $form->input('start', array('type'=>'hidden', 'value'=>$event['Event']['start']));
	echo $form->input('end', array('type'=>'hidden', 'value'=>$event['Event']['end']));
	echo $form->input('allday', array('type'=>'hidden', 'value'=>$event['Event']['allday']));
	echo  $form->end(array('label'=>'Save', 'name' => 'save')); 
?>