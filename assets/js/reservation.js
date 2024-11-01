var globalLang = [];

globalLang['res_timing'] = timing_txt;
globalLang['res_price'] = price_txt;
globalLang['res_img_tooltip_height'] = '48';
globalLang['res_img_tooltip_width'] = '48';
globalLang['tennisthor_swalert_okay_btn'] = ok_txt;

var player_image_text_click = false;
var fulCalendar, handleHorizontalScrollbar;

jQuery(function($){

var rxhtmlTag = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([a-z][^\/\0>\x20\t\r\n\f]*)[^>]*)\/>/gi;
$.htmlPrefilter = function( html ) {
    return html.replace( rxhtmlTag, "<$1></$2>" );
};		

	if(load_reservation_flag)
	{

		$(document).ready(function(){
			
			
			fulCalendar = $('#calendar');
			
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

			 fulCalendar.fullCalendar({
			 	locale: locale_code,
				schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
				defaultView: 'agendaDay',
				defaultDate: calendar_current_date,
				editable: false,
				selectable: true,
				slotDuration: slot_duration_var,
				slotLabelFormat: slotLabelFormat_,
				displayEventTime: true,
				timeFormat: slotLabelFormat_,		
				eventLimit: true, // allow "more" link when too many events
				minTime: min_time,
				maxTime: max_time,
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
				//// uncomment this line to hide the all-day slot
				allDaySlot: true,
				allDayText: '',
				/*eventSources: [
					{
						events: booking_time_slots,
						backgroundColor: 'white',
						borderColor: 'black'
					}
				],*/
		        eventMouseover: function (data, event, view) {

		            tooltip = '<div class="tooltiptopicevent tournament_scheduler_tooltip_div" style="background:'+data.backgroundColor+';">';
					if(data.court_image)
					{
						tooltip += '<div class="calendar_user_img_a">';	
						tooltip += '<a href="#" style="margin-right:3px;" target="_blank" class="player_a_cls"><img class="calendar_court_img" src="'+tennis_thor_domain+data.court_image+'" /></a>';
						tooltip += '</div>';
											
						tooltip += '<div class="calendar_user_img_a reservation_lbl" ><strong>'+lighting_text+'</strong>: <span class="'+data.court_light_badge+'">'+data.court_light_lbl+'</span></div>';					
						tooltip += '<div class="calendar_user_img_a reservation_lbl" ><strong>'+heating_text+'</strong>: <span class="'+data.court_heating_badge+'">'+data.court_heating_lbl+'</span></div>';					

					}		
					else if(data.booking == 'new')
					{
						var lbl = 'reservation_lbl';
						if(data.mode_code == 'coach' && data.textColor == 'white')
						{
							var lbl = 'reservation_lbl_white';
						}
						tooltip += '<div class="calendar_user_img_a '+lbl+'" ><strong>'+globalLang['res_timing']+'</strong>: '+data.reservation_time+'</div>';					
						if(typeof data.reservation_price != 'undefined')
				        {			
							tooltip += '<div class="calendar_user_img_a '+lbl+'" ><strong>'+globalLang['res_price']+'</strong>: '+data.reservation_price+'</div>';					
						}						
					}				
					else if(data.reservation_name)
					{
						tooltip += '<div class="calendar_user_img_a reservation_lbl_white" ><strong>'+data.reservation_name+'</strong> </div>';					
						tooltip += '<div class="calendar_user_img_a reservation_lbl_white" ><strong>'+globalLang['res_timing']+'</strong>: '+data.reservation_time+'</div>';					
					}
					else if(data.user_icons)
					{
						tooltip += '<div class="calendar_user_img_a reservation_lbl_white" ><strong>'+globalLang['res_timing']+'</strong>: '+data.reservation_time+'</div>';					
						
						if(data.reservation_show == "0")
						{
					        for (var i=0; i<data.user_icons.length; i++) {
					            //console.log(event.user_icons[i].image_path);
				            	
								tooltip += '<div class="calendar_user_img_a">';	
								tooltip += '<a href="'+data.user_icons[i].player_profile_url+'" style="margin-right:3px;" target="_blank" class="player_a_cls"><img style="max-height:'+globalLang['res_img_tooltip_height']+'px;max-width:'+globalLang['res_img_tooltip_width']+'px;" src="'+tennis_thor_domain+data.user_icons[i].image_path+'" /> '+data.user_icons[i].name+' </a>';
								tooltip += '</div>';			            	
					        }	
				        }					
					}
					
							
		            tooltip += '</div>';

		            $("body").append(tooltip);
		            $(this).mouseover(function (e) {
		                $(this).css('z-index', 10000);
		                $('.tooltiptopicevent').fadeIn('500');
		                $('.tooltiptopicevent').fadeTo('10', 1.9);
		            }).mousemove(function (e) {
		                $('.tooltiptopicevent').css('top', e.pageY + 10);
		                $('.tooltiptopicevent').css('left', e.pageX + 20);
		            });
		        },	 
		        eventMouseout: function (data, event, view) {
		            	$(this).css('z-index', 8);

		            $('.tooltiptopicevent').remove();

		        }, 			
				eventClick: function(event) {
					var target = $(event.target);

					if(typeof f == 'undefined')
					{
						if(event.booking == "edit" && !player_image_text_click)
						{				
							$(".loader").show();
							location.href = '/reservation/booking_players/'+event.club_id+'/'+event.reservation_id;
						}
						
						if($.trim(event.parmenent_reservation) == 'true')
						{
							$(".loader").show();
							var moment = $('#calendar').fullCalendar('getDate');
							var postData;
							postData = {
										booking_club_id: $('#club_id').val(),
										booking_sport_id: $('#sport_id').val(),
										booking_court_id: event.resourceId,
										booking_start_time: event.book_starttime,
										booking_end_time: event.book_endtime,
										booking_date: moment.format(),
										court_permanent_reservation_id: event.court_permanent_reservation_id,
									}
							$.ajax(
							{
								url : '/reservationparmenent/get_booking_url',
								type: "POST",
								data : postData,
								success:function(data, textStatus, jqXHR)
								{		
									console.log(data);
									jsonObj = JSON.parse(data);
									
									if(jsonObj.url_flag == 'true' || jsonObj.url_flag == true)
									{
										location.href = jsonObj.url;	
									}
									
									$(".loader").hide();
								},
								error: function(jqXHR, textStatus, errorThrown)
								{
									$(".loader").hide();
									//alert('Please refresh page and do action again.');
								}
							});							
							
						}
					}
					else
					{
						if(event.booking == "new")
						{
							$(".loader").show();
							location.href = tennis_thor_domain +'/reservation/index/'+f_enc_club_id;
						}
					}
					
					if(player_image_text_click)
						player_image_text_click = false;
				},
			    loading: function (bool) {
			       $(".loader").show();
			    },
				eventRender: function(event, element, view) {

					var event_html;
					event_html = '';
					if(event.user_icons){
						if(event.allDay){
							$(element).find('span:first').html('<img class="calendar_user_img" src="'+tennis_thor_domain+event.user_icons+'" />');
						}else{
							//console.log(event.user_icons);
							event_html = '<div class="col-sm-12 row"><div class="col-sm-7 row">';
							
							if(event.reservation_show == "0")
							{
						        for (var i=0; i<event.user_icons.length; i++) {
						            //console.log(event.user_icons[i].image_path);

						            event_html = event_html + '<div class="calendar_user_img_a"><a href="javascript:void(0)" class="player_a_cls"><img class="'+event.user_icons[i].image_class_name+'" src="'+tennis_thor_domain+event.user_icons[i].image_path+'" /> '+event.user_icons[i].name+' </a></div>';
					            	
						        }	
					        }
					        event_html += '</div>';
					        event_html += '<div class="col-sm-5 row event_right_cls"> '+ event.reservation_time;
					        event_html += '</div></div>';
					        
					        $(element).find('.fc-time').html(event_html);				
							
						}
					}
					
					if(event.court_image)
					{
						event_html = '<div class="col-sm-12 row court_calendar_main_div">';
							
							event_html += '<div class="col-sm-3 row">';
								event_html += '<img class="calendar_court_img" src="'+tennis_thor_domain+event.court_image+'" /> ';
							event_html += '</div>';
						
							event_html += '<div class="col-sm-9 row court_calendar_right"> ';
								event_html += '<label>'+lighting_text+':</label> <span class="'+event.court_light_badge+'">'+event.court_light_lbl+'</span>';
								event_html += '<div class="clearfix"></div>';
								event_html += '<label>'+heating_text+':</label> <span class="'+event.court_heating_badge+'">'+event.court_heating_lbl+'</span>';
							event_html += '</div>';
						
						event_html += '</div>';
						
						//$(element).find('.fc-time').html(event_html);
						$(element).find('span:first').html(event_html);
					}
								
					if(event.booking == 'new')
					{
						event_html = '<div class="col-sm-12 row"><div class="col-sm-12 row">';
						
						var event_price_html = '';
						if(typeof event.reservation_price != 'undefined')
				        {
							event_price_html = event.reservation_price;						
						}
						
						event_html = event_html + '<div class="calendar_user_img_a">'+event.reservation_time+'</div>';
						event_html = event_html + '<div class="calendar_user_img_a">'+event_price_html+'</div>';
						
				        event_html += '</div>';
				        event_html += '</div>';
				        
				        $(element).find('.fc-time').html(event_html);				
					}	
					if(event.booking == "busy")
					{
						event_html = '<div class="col-sm-12 row"><div class="col-sm-7 row">';
						
						event_html = event_html + '<div class="calendar_user_img_a"><a href="#" class="player_a_cls">'+event.reservation_name+'</a></div>';
				        event_html += '</div>';
				        event_html += '<div class="col-sm-5 row event_right_cls"> '+ event.reservation_time;
				        event_html += '</div></div>';
				        
				        $(element).find('.fc-time').html(event_html);			
					}				
							
				},		
				//resources: court_resource,
				resources: function(callback) {
				    setTimeout(function(){

				    	var moment = $('#calendar').fullCalendar('getDate');
				    	
					    $.ajax({
					    	
							url: tennisthor_wpadmin_url+"admin-post.php",
							dataType: "json",
							type: "POST",
							cache: false,
							data: {
								action:'tennisthor_res_reload_courts_hook',
								club_id:$('#club_id').val(),
								sport_id:$('#sport_id').val(),
								current_date:moment.format(),
							},
							beforeSend:function(xhr)
							{						
								$(".loader").show();	  	
							}					
						}).then(function(resources) {
							fulCalendar.fullCalendar('removeEventSources');
							//court_resources = JSON.parse(resources);
							callback(resources.courts);
							$('#calendar_show').hide();
							$('#calendar').show();
							if($.trim(resources.calendar_show) == 'false')
							{
								$('#calendar').hide();
								$('#calendar_show').html(resources.court_rules_html);
								$('#calendar_show').show();
								$(".loader").hide();
								return false;
							}

							if(resources.courts.length <= 0)
							{
								$(".loader").hide();
								return false;							
							}
							
							var postData,res_array;
							postData = [];
							res_array = [];
							for(var key in resources.courts) {
								res_array[key] = resources.courts[key].id;    
							}					

							postData = {action:'tennisthor_res_reload_events_hook',sport_id:$('#sport_id').val(),club_id: $('#club_id').val(),resource_array:res_array,current_date:moment.format(),mode: $('#admin_timeline_mode').val()}
							
							$.ajax(
							{
								url : tennisthor_wpadmin_url+'admin-post.php',
								type: "POST",
								data : postData,
								success:function(data, textStatus, jqXHR)
								{			

															
									booking_time_slots = JSON.parse(data);
									fulCalendar.fullCalendar('removeEventSources');
									fulCalendar.fullCalendar( 'addEventSource', booking_time_slots.event_time_slot );
									
									fulCalendar.fullCalendar('option', 'minTime', booking_time_slots.min_time);							
									fulCalendar.fullCalendar('option', 'maxTime', booking_time_slots.max_time);	
									
									if(col_setting)
									{								
										fulCalendar.fullCalendar('option', 'viewRender', handleHorizontalScrollbar(fulCalendar));	
										fulCalendar.fullCalendar('option', 'windowResize', handleHorizontalScrollbar(fulCalendar));	
									}
									
									if($(window).width() < 768)
									{
										fulCalendar.fullCalendar('option', 'contentHeight', 600);		
									}

									$(".timelinecal").removeClass("active_date");
									$('#date_'+ moment.format('YYYY-MM-DD')).addClass("active_date"); 																							
						
									load_cal_events();							

									$('#load_time_line_days').scrollLeft($('.active_date').offset().left);
																
									$(".loader").hide();
								},
								error: function(jqXHR, textStatus, errorThrown)
								{
									$(".loader").hide();
									//alert('Please refresh page and do action again.');
								}
							});						
							
						})
					},0);
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
				},
			    viewRender: function(view, element) {
			        //Do something
					
			    }		
			});
			
			load_cal_events();
			
			
	          		
	            
	         
			
		});

	}
	
	
	

});

function load_timeline_by_calendar(ele)
{
	jQuery(function($){
	    $(".timelinecal").removeClass("active_date");
	    $(ele).addClass("active_date");   
	    var sel_date = $(ele).attr('id');
	    sel_date = sel_date.replace("date_","");
	    //defaultDate

	    $('#calendar').fullCalendar('gotoDate', sel_date);
	    fulCalendar.fullCalendar('refetchResources');
	    
	    
    });	
}

function load_settings()
{
	jQuery(function($){	
		$(".loader").show();
		postData = {}	
		$.ajax(
		{
			url : '/reservationsettings/index',
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{	
				
				$('#reservation_settings_popup').modal('show');
				$('#reservation_settings_form').html(data);
				
				load_radio_button();				
				
				$(".loader").hide();		
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				$(".loader").hide();
				//alert('Please refresh page and do action again.');
			}
		});		
	});		
}


function get_calendar_sport_change(club_id, sport)
{
	fulCalendar.fullCalendar('refetchResources');		
}

function get_calendar_mode_change()
{
	fulCalendar.fullCalendar('refetchResources');		
}		

function load_cal_events()
{
	jQuery(function($){	
		$('.player_a_cls').click(function(){
			//player_image_text_click = true;
		});
			
		$('.fc-prev-button').click(function(){
			fulCalendar.fullCalendar('refetchResources');
		});

		$('.fc-next-button').click(function(){
			fulCalendar.fullCalendar('refetchResources');
		});	
		
		$(".fc-today-button").click(function() {
		    fulCalendar.fullCalendar('refetchResources');
		});
	});		
}

function load_timeline_calendar(type)
{
	jQuery(function($){	
		$(".loader").show();
		postData = {action:'tennisthor_load_timeline_calendar_hook', type: type, first_date: $('#first_date').val(), last_date: $('#last_date').val()}	
		$.ajax(
		{
			url : tennisthor_wpadmin_url+'admin-post.php',
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{	
				jsonObj = JSON.parse(data);
				
				$('#load_time_line_days').html(jsonObj.timeline_calc_html);
				$('#first_date').val(jsonObj.first_date);
				$('#last_date').val(jsonObj.last_date);
				
				var moment = $('#calendar').fullCalendar('getDate');
				$(".timelinecal").removeClass("active_date");
				$('#date_'+ moment.format('YYYY-MM-DD')).addClass("active_date");				
				
				$(".loader").hide();		
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				$(".loader").hide();
				//alert('Please refresh page and do action again.');
			}
		});		
	});
}


function change_language(lang)
{
	setTennisCookie('tennis_lang',lang,365);
	location.reload(true);
}

function setTennisCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getTennisCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}	