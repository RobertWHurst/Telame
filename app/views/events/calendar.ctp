<?php
//INCLUDES
$html->css(array(
	'base',
	'gallery',
	'summary',
	'tall_header',
	'main_sidebar',
	'calendar/fullcalendar.css',
), null, array('inline' => false));
$js = array(
	'jquery',
	'base', 
	'main_sidebar',
	'calendar/fullcalendar',
	'calendar/jquery-ui-custom'
);
foreach ($js as $j) {
	$javascript->link($j, false);
}

//page title

?>

<div id="calendar"></div>

<script type='text/javascript'>

    $(document).ready(function() {
        $('#calendar').fullCalendar({});
    });

</script>