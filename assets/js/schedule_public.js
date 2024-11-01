var fulCalendar, handleHorizontalScrollbar;
//SET resources width by js
function handleHorizontalScrollbar(fulCalendar)
{
	const minColumnWidthInPixels = 180; // up to you   
	function getContainerWidth()
	{
		return fulCalendar.parent().outerWidth();
	}
	
	function getAgendaWidthInPercent()
	{
	    const containerWidthInPixels = getContainerWidth();
	    const numberOfColumns = fulCalendar.fullCalendar("getResources").length;
	    const firstColumnWidthInPixels = fulCalendar.find(".fc-axis.fc-widget-header").outerWidth();
	    const sumOfBorderWidthsInPixels = numberOfColumns;
	    const expectedTotalWidthInPixels = minColumnWidthInPixels * numberOfColumns
	        + firstColumnWidthInPixels
	        + sumOfBorderWidthsInPixels;
	    const agendaWidthInPercent = expectedTotalWidthInPixels / containerWidthInPixels * 100;
	    return Math.max(agendaWidthInPercent, 100); // should not be less than 100% anyway			
	} 

	return function view(view) 
	{ 
  		return view.el.css("width", getAgendaWidthInPercent() + "%"); 
	};  	
}

jQuery(document).ready(function(){
	
	fulCalendar = jQuery('#calendar');

	 fulCalendar.fullCalendar({		 	
		schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
		defaultView: 'agendaDay',
		defaultDate: calendar_current_date,
		editable: false,
		selectable: false,
		slotDuration: '00:15:00',
		slotLabelFormat: slotLabelFormat_,
		displayEventTime: true,
		timeFormat: slotLabelFormat_,		
		eventLimit: true, // allow "more" link when too many events
		eventOverlap: false,		
		droppable: false,
		dragRevertDuration: 0,
		minTime: min_time,
		contentHeight: 'auto',
		header: {
			left: 'prev,next today',
			center: 'title',
			right: ''
		},
		views: {
			agendaTwoDay: {
				type: 'agenda',
				duration: { days: 2 },
				groupByResource: true
			}
		},
		allDaySlot: false,
		allDayText: '',

		defaultTimedEventDuration: slot_duration_for_event,
    	forceEventDuration: true,
    
		eventClick: function(event) {
		},
	    loading: function (bool) {
	       jQuery(".loader").show();
	    },
	    
        eventMouseover: function (data, event, view) {

            tooltip = '<div class="tooltiptopicevent tournament_scheduler_tooltip_div col-sm-12 row" style="background:'+data.backgroundColor+';">';
			if(typeof data.teams != 'undefined')
			{	
				tooltip += '<div class="calendar_user_img_a" ><strong>'+data.game_lbl+'</strong>: '+data.title_without_team+'</div>';					
				tooltip += '<div class="calendar_user_img_a"><strong>'+data.start_lbl+'</strong>: '+data.game_start_date+'</div>';
					
				for (var i=0; i<data.teams.length; i++) {
					tooltip += '<div class="calendar_user_img_a">';	
					tooltip += '<a href="#" style="margin-right:3px;" target="_blank" class="player_a_cls"><img style="'+data.teams[i].image_style_tag_tooltip+'" src="'+data.teams[i].image_path+'" /> '+data.teams[i].name+' </a>';
					tooltip += '</div>';
				}									
			}
			else
			{
				tooltip += '<div class="calendar_user_img_a"><strong>'+data.game_lbl+'</strong>: '+data.title+'</div>';					
				tooltip += '<div class="calendar_user_img_a"><strong>'+data.start_lbl+'</strong>: '+data.game_start_date+'</div>';
			} 
            tooltip += '</div>';

            jQuery("body").append(tooltip);
            jQuery(this).mouseover(function (e) {
                jQuery(this).css('z-index', 10000);
                jQuery('.tooltiptopicevent').fadeIn('500');
                jQuery('.tooltiptopicevent').fadeTo('10', 1.9);
            }).mousemove(function (e) {
                jQuery('.tooltiptopicevent').css('top', e.pageY + 10);
                jQuery('.tooltiptopicevent').css('left', e.pageX + 20);
            });
        },	 
        eventMouseout: function (data, event, view) {
            jQuery(this).css('z-index', 8);

            jQuery('.tooltiptopicevent').remove();

        },        
        eventDragStart: function () {
            tooltip.hide()
        },
        viewDisplay: function () {
            tooltip.hide()
        },           
		eventRender: function(event, element, view) {
		
			var moment = jQuery('#calendar').fullCalendar('getDate')			
			var remove_games_url_temp;

			event_html = '<div class="col-sm-12 row" id="'+event.id+'"><div class="col-sm-12 row">';			
				if(typeof event.teams != 'undefined')
				{	
					event_html = event_html + '<div class="calendar_user_img_a">'+event.title_without_team+'</div>';
						
					event_html = event_html + '<div class="calendar_user_img_a">';		
					for (var i=0; i<event.teams.length; i++) {
						event_html = event_html + '<a href="javascript::void(0);" style="margin-right:3px;" target="_blank" class="player_a_cls"><img style="'+event.teams[i].image_style_tag+'" src="'+event.teams[i].image_path+'" /> '+event.teams[i].name+' </a>';
					}				
					event_html = event_html + '</div>';
				}
				else
				{
					event_html = event_html + '<div class="calendar_user_img_a">'+event.title+'</div>';
				}
				//event_html = event_html + '<div class="calendar_user_img_a">'+event.title+'</div>';
	        event_html += '</div>';
	        event_html += '</div>';
	        
	        jQuery(element).find('.fc-time').html(event_html);			
	        jQuery(element).find('.fc-title').html('');	
		},		
     
		resources: function(callback) {
		    jQuery.ajax({
		    	
				url: tennisthor_wpadmin_url+"admin-post.php",
				dataType: "json",
				type: "POST",
				cache: false,
				data: {f:true, action:'tennisthor_sch_reload_courts_hook',tennisthor_club_id:enc_club_id,tennisthor_tour_id:enc_tour_id},
				beforeSend:function(xhr)
				{						
					jQuery(".loader").show();	  	
				}					
			}).then(function(resources) {
				fulCalendar.fullCalendar('removeEventSources');
				callback(resources);
				reload_games();
				jQuery(".loader").hide();
			});

		},
		select: function(start, end, jsEvent, view, resource) {
			console.log(
				'select',
				start.format(),
				end.format(),
				resource ? resource.id : '(no resource)'
			);
		},
		dayClick: function(date, jsEvent, view, resource) {
			console.log(
				'dayClick',
				date.format(),
				resource ? resource.id : '(no resource)'
			);
			tooltip.hide();
		}	
	});
	
	load_cal_events(); 
    
});

function load_cal_events()
{		
	jQuery('.fc-prev-button').click(function(){
		fulCalendar.fullCalendar('refetchResources');
	});

	jQuery('.fc-next-button').click(function(){
		fulCalendar.fullCalendar('refetchResources');
	});	
	
	jQuery(".fc-today-button").click(function() {
	    fulCalendar.fullCalendar('refetchResources');
	});		
}

function reload_games()
{
	var moment = jQuery('#calendar').fullCalendar('getDate');
				
	var postData;
	postData = {
			action:'tennisthor_sch_reload_games_hook',
			current_date:moment.format(),
			tennisthor_club_id:enc_club_id,
			tennisthor_tour_id:enc_tour_id,
			f:true
		}
	
	jQuery.ajax(
	{
		url : tennisthor_wpadmin_url+"admin-post.php",
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR)
		{			
			schedule_slots = JSON.parse(data);
			if(jQuery(window).width() < 768)
			{
				fulCalendar.fullCalendar('option', 'contentHeight', 600);		
			}
			fulCalendar.fullCalendar('removeEventSources');
			fulCalendar.fullCalendar( 'addEventSource', schedule_slots.game_slots );

			//fulCalendar.fullCalendar('option', 'viewRender', handleHorizontalScrollbar(fulCalendar));	
			//fulCalendar.fullCalendar('option', 'windowResize', handleHorizontalScrollbar(fulCalendar));		
			
			fulCalendar.fullCalendar('option', 'contentHeight', 'auto');		
			
			jQuery(".loader").hide();
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			jQuery(".loader").hide();
		}
	});	
}