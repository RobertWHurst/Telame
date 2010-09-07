<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'gallery',
	'summary',
	'tall_header',
	'main_sidebar',
	'calendar/fullcalendar.css',
));
$this->set('script_for_layout', array(
	'jquery',
	'base', 
	'main_sidebar',
	'calendar/fullcalendar',
	'calendar/jquery-ui-custom'
));
//page title

?>

<div id="calendar"></div>

<script type='text/javascript'>

    $(document).ready(function() {
        $('#calendar').fullCalendar({});
    });

</script>