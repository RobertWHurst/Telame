<?php
//INCLUDES
$html->css(array(
	'base',
	'tall_header',
	'main_sidebar',
	'calendar/fullcalendar'
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
$this->set('title_for_layout', __('calendar_title', true));

//page title
?>
<div id="page_head">
	<h1 class="page_title"><?php echo $user['User']['first_name'] . '\'s ' . __('calendar_title', true); ?></h1>
</div>
<div id="page_body">
	<div id="eventdata"></div>
	<div id="calendar"></div>
	<div class="no_js">
		<p><?php __('calendar_no_js'); ?></p>
	</div>

	<script type="text/javascript">
		$(document).ready(function() {
			$('div.no_js').remove();
			$("#eventdata").hide();
			$('#calendar').fullCalendar({
				header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
				editable: true,
				weekMode: 'variable',
				dayClick: function(date, allDay, jsEvent, view) {
					$("#eventdata").show();
					$("#eventdata").load("/<?php echo $currentUser['User']['slug']?>/calendar/add/" + allDay + "/" + $.fullCalendar.formatDate(date, "dd/MM/yyyy/HH/mm"));
				},
				eventClick: function(calEvent, jsEvent, view) {
					$("#eventdata").show();
					$("#eventdata").load("/<?php echo $currentUser['User']['slug']?>/calendar/edit/" + calEvent.id);
				},
				eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc) {
					if (dayDelta >= 0) {
						dayDelta = "+" + dayDelta;
					}
					if (minuteDelta >= 0) {
						minuteDelta = "+" + minuteDelta;
					}
					$.post("/<?php echo $currentUser['User']['slug']?>/calendar/move/" + event.id + "/" + dayDelta + "/" + minuteDelta + "/" + allDay);
					},
				eventResize: function(event, dayDelta, minuteDelta, revertFunc) {
					if (dayDelta >= 0) {
						dayDelta = "+" + dayDelta;
					}
					if (minuteDelta >= 0) {
						minuteDelta = "+" + minuteDelta;
					}
					$.post("/<?php echo $currentUser['User']['slug']?>/calendar/resize/" + event.id + "/" + dayDelta + "/" + minuteDelta);
				},
				events: "/<?php echo $user['User']['slug']?>/calendar/feed",
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
</div>