<input type="button" value="Cancel" onclick="back();">
<script type="text/javascript">
    function back() {
        window.location.href ="/calendar";
	}
</script><!-- app/views/events/add.ctp -->
<?php 
	echo $form->create('Event', array('target'=> '_parent'));
	echo $form->input('title' , array('label' => 'Event title'));
	echo '<br />At: ' . $displayTime;
	echo $form->input('start', array('type'=>'hidden', 'value'=>$event['Event']['start']));
	echo $form->input('end', array('type'=>'hidden', 'value'=>$event['Event']['end']));
	echo $form->input('allday', array('type'=>'hidden', 'value'=>$event['Event']['allday']));
	echo  $form->end(array('label'=>'Save', 'name' => 'save')); 
?>