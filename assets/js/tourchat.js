
	
jQuery(document).ready(function(){
	
	// Run the init method on document ready:
	chat.init();
	
	jQuery("#chatText").keypress(function (e) {
	    if(e.which == 13 && !e.shiftKey) {      
	    		       
	        jQuery(this).closest("form").submit();
	        e.preventDefault();
	        return false;
	    }
	});	
	
	jQuery('.chat_write_right_select').change(function (e){
		jQuery('.loader').show();
		location.href = chat_write_url+jQuery(this).val();
	});
	
	if(jQuery("#chatText").length > 0)
	{
		jQuery("#chatText").focus();	
	}
	
	if(typeof showMeridian_js != 'undefined')
	{
	    jQuery('.date_start_registration').datetimepicker({
	        //language:  'fr',
	        weekStart: 1,
	        todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			forceParse: 0,
	        showMeridian: showMeridian_js
	    });	
	    
		jQuery('#game_schedule_frm').validate({

			rules: {
				date_start_registration: {
					required: true,
				},
			},

			messages: {
				date_start_registration: {
					required: transl_1301,
				},
			},    

			errorPlacement: function(error, element) {
				
				if(element.attr("name") == "date_start_registration")
				{
					error.appendTo( element.parent().parent());
				}
				else
				{
					error.insertAfter(element);	
				}
			},		

			submitHandler: function(form) {
				var frm_flg;
				frm_flg = true;
				if(frm_flg == true)
			        form.submit();				
		    }    
		});	 	    	
	}
	
});

var refresh_chat_request = 5000;
var refresh_chat_activity = 0;

var chat = {
	
	// data holds variables for use in the class:
	
	data : {
		lastID 		: 0,
		noActivity	: 0
	},
	
	// Init binds event listeners and sets up timers:
	
	init : function(){
		
		// Converting the #chatLineHolder div into a jScrollPane,
		// and saving the plugin's API in chat.data:
		
		chat.data.jspAPI = jQuery('#chatLineHolder');
		
		// We use the working variable to prevent
		// multiple form submissions:
		
		var working = false;
		
		// Submitting a new chat entry:		
		jQuery('#submitForm').submit(function(){
			
			load_permission_salert();
			if(typeof admin_permission_flag != 'undefined')
				if(admin_permission_flag == false)
					return false;			
			
			var text = jQuery('#chatText').val();
			
			text = jQuery.trim(text);
			
			if(text.length == 0){
				return false;
			}
			
			if(working) return false;
			working = true;

			// Assigning a temporary ID to the chat:
			var tempID = 't'+Math.round(Math.random()*1000000),
				params = {
					id			: tempID,
					author		: logged_user_name,
					gravatar	: logged_user_avtar,
					is_logged_in_user	: enc_logged_user,
					text		: text.replace(/</g,'&lt;').replace(/>/g,'&gt;')
				};

			// Using our addChatLine method to add the chat
			// to the screen immediately, without waiting for
			// the AJAX request to complete:
			
			//chat.addChatLine(jQuery.extend({},params));
			
			// Using our tzPOST wrapper method to send the chat
			// via a POST AJAX request:
			var form_data = jQuery(this).serialize();
			jQuery('#chatText').val('');
			
			jQuery.tzPOST('submitChat',form_data,function(r){
				working = false;
				
				//jQuery('#chatText').val('');
				jQuery('div.chat-'+tempID).remove();
				
				params['id'] = r.insertID;
				//chat.addChatLine(jQuery.extend({},params));
				chat.getChats();
			});
			
			return false;
		});
		
		// Logging the user out:
		
		// Self executing timeout functions		
		(function getChatsTimeoutFunction(){
			chat.getChats(getChatsTimeoutFunction);
		})();
		
		(function getUsersTimeoutFunction(){
			chat.getUsers(getUsersTimeoutFunction);
		})();
		
	},
	
	
	// The render method generates the HTML markup 
	// that is needed by the other methods:
	
	render : function(template,params){
		
		var arr = [];
		switch(template){
			case 'dateHtml':
				arr = [
					'<div class="chat-message chat chat-date-',params.id,' rounded">',
					'<div class="message" style="background-color:#fff;margin-right;margin-right: 0px;text-align: center;">',
						'<a class="message-author" href="#">',params.date,'</a>',
					'</div></div>'];			
			break;
			case 'chatLine':
			
				
			
				var date_html = '';
				var date_html_flag = false;
				
				if(jQuery.trim(params.prev_date) == "")
				{
					date_html_flag = true;
				}
				
				if(params.prev_date != params.date && jQuery.trim(params.prev_date) != "")
				{	
					date_html_flag = true;			
				}		
				if(jQuery('.date_cls_'+params.date_for_class).length > 0)
				{
					date_html_flag = false;	
				}
								
				if(date_html_flag)
				{					
					date_html = '<div class="chat-message chat chat-date-'+params.id+' rounded date_cls_'+params.date_for_class+'">';
					date_html += '<div class="message" style="background-color:#fff;margin-right: 0px;text-align: center;">';
					date_html += '<a class="message-author" href="#">'+params.date+'</a>';
					date_html += '</div></div>';					
				}		
			
				var is_logged_in_user_cls = 'left'
				if(params.is_logged_in_user == enc_logged_user)
				{
					is_logged_in_user_cls = 'right'	
				}
				
				arr = [
					date_html,
					'<div class="chat-message ',is_logged_in_user_cls,' chat chat-',params.id,' rounded">',
					'<img src="',params.gravatar,'" class="message-avatar" onload="this.style.visibility=\'visible\'" /> ',
					'<div class="message" style="background-color:',params.color,'">',
						'<a class="message-author" href="#">',params.author,'</a>',
						'<span class="message-date"> ',params.time,'</span>',
						'<span class="message-content">',params.text,'</span>',
					'</div>'];
			break;
			
			case 'user':
			
				var confm_msg = 'Are you sure to block this user?';
				if(params.blocked == "1")
				{
					confm_msg = 'Are you sure to unblock this user?';	
				}
				
				var block_cls = 'fa fa-unlock';
				if(params.blocked == "1")
					block_cls = 'fa fa-lock';
				
				var block_html;
				if(typeof block_url != 'undefined')
				{				
					block_html = '<a href="'+block_url+params.enc_user_id+'" onclick="if(!confirm(\''+confm_msg+'\')){ return false;}"><span class="pull-right "><i class="'+block_cls+'" aria-hidden="true"></i></span></a>';
					if(typeof is_admin_tp != 'undefined')
					{
						block_html = '<a href="'+block_url+params.enc_user_id+is_admin_tp+'" onclick="if(!confirm(\''+confm_msg+'\')){ return false;}"><span class="pull-right"><i class="'+block_cls+'" aria-hidden="true"></i></span></a>';	
					}
				}
				
				
				if(jQuery.trim(is_admin) == "")
					block_html = '';
				
				arr = [
					'<div class="chat-user" style="background-color: ',params.color,';">',
						block_html,
						'<img src="',params.gravatar,'" class="chat-avatar" onload="this.style.visibility=\'visible\'" /> ',
						'<div class="chat-user-name">',
							'<a href="https://www.tennisthor.com/userchat/index/'+params.enc_user_id+'" target="_blank">',params.name,'</a>',
						'</div>',
					'</div>'
					];
			break;
		}
		
		// A single array join is faster than
		// multiple concatenations
		
		return arr.join('');
		
	},
	
	// The addChatLine method ads a chat entry to the page
	
	addChatLine : function(params){
		
		// All times are displayed in the user's timezone
		var d = new Date();
		if(params.time) {
			
			// PHP returns the time in UTC (GMT). We use it to feed the date
			// object and later output it in the user's timezone. JavaScript
			// internally converts it for us.
			d.setUTCHours(params.time.hours,params.time.minutes);
		}
		
		if(typeof params.time == 'undefined')
		{
			params.time = (d.getHours() < 10 ? '0' : '' ) + d.getHours()+':'+
					  (d.getMinutes() < 10 ? '0':'') + d.getMinutes();			
		}
		else
		{
			params.time = params.time.hours+':'+params.time.minutes;	
		}

		var markup = chat.render('chatLine',params),
			exists = jQuery('#chatLineHolder .chat-'+params.id);

		if(exists.length){
			exists.remove();
		}
		
		if(!chat.data.lastID){
			// If this is the first chat, remove the
			// paragraph saying there aren't any:
			
			jQuery('#chatLineHolder .noChats').remove();
			
		}
		
		// If this isn't a temporary chat:
		if(params.id.toString().charAt(0) != 't'){
			var previous = jQuery('#chatLineHolder .chat-'+(+params.id - 1));
			if(previous.length){
				previous.after(markup);
			}
			else chat.data.jspAPI.append(markup);
		}
		else chat.data.jspAPI.append(markup);
		
		// As we added new content, we need to
		// reinitialise the jScrollPane plugin:
				
		//chat.data.jspAPI.scrollToBottom(true);
		
		
		
		jQuery("#chatLineHolder").scrollTop(jQuery("#chatLineHolder")[0].scrollHeight);
		
	},
	addDateHtml : function(params){
		var markup = chat.render('dateHtml',params);		
		chat.data.jspAPI.append(markup);		
	},
	
	// This method requests the latest chats
	// (since lastID), and adds them to the page.
	
	getChats : function(callback){
		jQuery.tzPOST('getChats',{lastID: chat.data.lastID},function(r){

			for(var i=0;i<r.chats.length;i++){
				//console.log(r.chats[i].prev_date);
				
				/*
				if(jQuery.trim(jQuery('#chatLineHolder').html()) == "" && jQuery.trim(r.chats[i].prev_date) == "")
				{
					chat.addDateHtml(r.chats[i]);
				}
				
				if(r.chats[i].prev_date != r.chats[i].date && jQuery.trim(r.chats[i].prev_date) != "")
				{				
					console.log(r.chats[i].id);
					chat.addDateHtml(r.chats[i]);
				}
				
				chat.addDateHtml(r.chats[i]);
				*/
				//console.log(r.chats[i].id);
				//console.log(r.chats[i].text);
				chat.addChatLine(r.chats[i]);
			}
			
			if(r.chats.length){
				refresh_chat_activity = 0;
				chat.data.noActivity = 0;
				chat.data.lastID = r.chats[i-1].id;
			}
			else{
				// If no chats were received, increment
				// the noActivity counter.
				
				chat.data.noActivity++;
				
			}
			
			if(!chat.data.lastID){
				chat.data.jspAPI.html('<div class="chat-user"><p class="noChats">'+no_message_yet_txt+'</p></div>');
			}
			
			// Setting a timeout for the next request,
			// depending on the chat activity:
			
			var nextRequest = 10000;			
			if(chat.data.noActivity > refresh_chat_activity)
			{
				nextRequest = (chat.data.noActivity * refresh_chat_request) + nextRequest;
				if(nextRequest <= 120000)
				{
					
				}
				else
				{
					nextRequest = 120000
				}
			}
			
			//console.log('nextRequest:'+ nextRequest+', chat.data.noActivity:'+chat.data.noActivity+', refresh_chat_activity:'+refresh_chat_activity);
			if(chat.data.noActivity > 0)
				refresh_chat_activity++;
				
			setTimeout(callback,nextRequest);
		});
	},
	
	// Requesting a list with all the users.
	
	getUsers : function(callback){
		jQuery.tzPOST('getUsers',function(r){
			
			var users = [];
			
			for(var i=0; i< r.users.length;i++){
				if(r.users[i]){
					users.push(chat.render('user',r.users[i]));
				}
			}
			
			var message = '';
			
			if(r.total<1){
				message = 'No guests available';
				users.push('<div class="chat-user"><p class="count">'+message+'</p></div>');
			}
			else {
				message = '';
			}
			
			
			
			jQuery('#chatUsers').html(users.join(''));
			
			setTimeout(callback,60000);
		});
	},
	
	// This method displays an error message on the top of the page:
	
	displayError : function(msg){
		var elem = jQuery('<div>',{
			id		: 'chatErrorMessage',
			html	: msg
		});
		
		elem.click(function(){
			jQuery(this).fadeOut(function(){
				jQuery(this).remove();
			});
		});
		
		setTimeout(function(){
			elem.click();
		},5000);
		
		elem.hide().appendTo('body').slideDown();
	}
};

// Custom GET & POST wrappers:

jQuery.tzPOST = function(action,data,callback){
	jQuery.post(tennisthor_wpadmin_url+'admin-post.php?action=tennisthor_chat_hook&type='+action+'&tennisthor_tour_id='+enc_tour_id+'&tennisthor_room_id='+enc_room_id,data,callback,'json');
}

jQuery.tzGET = function(action,data,callback){
	jQuery.get(chat_url+'?action='+action,data,callback,'json');
}

// A custom jQuery method for placeholder text:

jQuery.fn.defaultText = function(value){
	
	var element = this.eq(0);
	element.data('defaultText',value);
	
	element.focus(function(){
		if(element.val() == value){
			element.val('').removeClass('defaultText');
		}
	}).blur(function(){
		if(element.val() == '' || element.val() == value){
			element.addClass('defaultText').val(value);
		}
	});
	
	return element.blur();
}


function get_player_list(id)
{
	load_permission_salert();
	if(typeof admin_permission_flag != 'undefined')
		if(admin_permission_flag == false)
			return false;	
			
	var postData;
	postData = {};
	jQuery(".loader").show();
	jQuery.ajax(
	{
		url : add_player_url,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR)
		{	
			jQuery('#chat_players_popup').modal('show');
			jQuery('#chat_players_popup_data').html(data);	

			jQuery(document).ready(function(){
			   var tableObj = jQuery('#adminlistplayers').DataTable({
			        pageLength: 25,
			        responsive: true,
			        "order": [[1, "asc" ]],
			        "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 0,2 ] },{ "searchable": false, "aTargets": [ 0,2 ] } ],
			        "language": get_datatable_language_strings(),
			        initComplete: function () { 
						jQuery('#adminlist').attr('style','width:100%;');
			        }, 	
			        "drawCallback": function( settings ) {
				    }			    
			    });
			    
				jQuery( "#adminlistplayers_filter input" ).focus(function() {
					jQuery( "#adminlistplayers_filter input" ).val('');					
					tableObj.search('').draw();
				});			    
			});			
			
			jQuery(".loader").hide();	  	
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			jQuery(".loader").hide();
			//alert('Please refresh page and do action again.');
		}
	});		
}

function add_in_chat_room(enc_user_id)
{
	var postData;
	postData = {};
	jQuery(".loader").show();
	jQuery.ajax(
	{
		url : add_to_chat_room_url+enc_user_id,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR)
		{	
			jQuery('.remove_from_chat_room'+enc_user_id).show();
			jQuery('.add_in_chat_room'+enc_user_id).hide();	
			jQuery(".loader").hide();	  	
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			jQuery(".loader").hide();
			//alert('Please refresh page and do action again.');
		}
	});		
}

function remove_from_chat_room(enc_user_id)
{
	var postData;
	postData = {};
	jQuery(".loader").show();
	jQuery.ajax(
	{
		url : remove_from_chat_room_url+enc_user_id,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR)
		{	
			jQuery('.remove_from_chat_room'+enc_user_id).hide();
			jQuery('.add_in_chat_room'+enc_user_id).show();
			jQuery(".loader").hide();	  	
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			jQuery(".loader").hide();
			//alert('Please refresh page and do action again.');
		}
	});		
}


function get_staff_list(id)
{
	load_permission_salert();
	if(typeof admin_permission_flag != 'undefined')
		if(admin_permission_flag == false)
			return false;	
			
	var postData;
	postData = {};
	jQuery(".loader").show();
	jQuery.ajax(
	{
		url : add_staff_url,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR)
		{	
			jQuery('#chat_staff_popup').modal('show');
			jQuery('#chat_staff_popup_data').html(data);	

			jQuery(document).ready(function(){
			   var tableObj = jQuery('#adminlistplayers').DataTable({
			        pageLength: 25,
			        responsive: true,
			        "order": [[1, "asc" ]],
			        "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 0,2 ] },{ "searchable": false, "aTargets": [ 0,2 ] } ],
			        "language": get_datatable_language_strings(),
			        initComplete: function () { 
						jQuery('#adminlist').attr('style','width:100%;');
			        }, 	
			        "drawCallback": function( settings ) {
				    }			    
			    });
			    
				jQuery( "#adminlistplayers_filter input" ).focus(function() {
					jQuery( "#adminlistplayers_filter input" ).val('');					
					tableObj.search('').draw();
				});			    
			});			
			
			jQuery(".loader").hide();	  	
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			jQuery(".loader").hide();
			//alert('Please refresh page and do action again.');
		}
	});		
}