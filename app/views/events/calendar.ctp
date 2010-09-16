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
	'calendar/jquery-ui-custom',

);
foreach ($js as $j) {
	$javascript->link($j, false);
}

//page title

?>
<div id="eventdata"></div>
<div id="calendar"></div>

<script type="text/javascript">
$(document).ready(function() {
	$("#eventdata").hide();
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		editable: true,
		dayClick: function(date, allDay, jsEvent, view) {
			$("#eventdata").show();
			$("#eventdata").load("<?php echo Dispatcher::baseUrl();?>/calendar/add/" + allDay + "/" + $.fullCalendar.formatDate(date, "dd/MM/yyyy/HH/mm"));
		},
		eventClick: function(calEvent, jsEvent, view) {
			$("#eventdata").show();
			$("#eventdata").load("<?php echo Dispatcher::baseUrl();?>/calendar/edit/" + calEvent.id);
		},
		eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc) {
			if (dayDelta >= 0) {
				dayDelta = "+" + dayDelta;
			}
			if (minuteDelta >= 0) {
				minuteDelta = "+" + minuteDelta;
			}
			$.post("/calendar/move/" + event.id + "/" + dayDelta + "/" + minuteDelta + "/" + allDay);
			},
		eventResize: function(event, dayDelta, minuteDelta, revertFunc) {
			if (dayDelta >= 0) {
				dayDelta = "+" + dayDelta;
			}
			if (minuteDelta >= 0) {
				minuteDelta = "+" + minuteDelta;
			}
			$.post("/calendar/resize/" + event.id + "/" + dayDelta + "/" + minuteDelta);
		},
		events: "/calendar/feed",
		<?php if (isset($openYear)) { ?>
		year: <?php echo $openYear.',';
		}
		if (isset($openMonth)) { ?>
		month: <?php echo $openMonth.',';
		}
		if (isset($openDay)) { ?>
		date: <?php echo $openDay.',';
		} ?>
	});
})
</script>