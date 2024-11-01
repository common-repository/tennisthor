<?php
wp_enqueue_script('tour-js-7', plugins_url('../../assets/js/schedule_public.js',__FILE__));
?>
<div class="m-t clearfix"></div>  
<div class="col-sm-12 tournament_scheduler">
	<div id='calendar'></div>
</div>

<script>
	var enc_club_id = '<?php echo $body['enc_club_id'];?>';		
	var enc_tour_id = '<?php echo $body['enc_tour_id'];?>';			
	var calendar_current_date = '<?php echo date("Y-m-d", strtotime($body['date_st']));?>';		
	var slotLabelFormat_ = '<?php echo $body['slotLabelFormat'];?>';
	var slot_duration_for_event = '<?php echo $body['slot_duration_for_event'];?>';
	var min_time = '<?php echo date("H:i", strtotime($body['tour_info']["date_start"]));?>';
</script>


<style>
	.fc-resizer.fc-end-resizer {
	    display: none !important;
	}	
		
	.fc-time-grid .fc-slats td
	{
		height: 2.5em !important;
	}		
</style>