var globalLang = [];

globalLang['registration_confirmpass_same'] = js_msg1_txt;
globalLang['registration_allowed_alphanumeric'] = js_msg2_txt;
globalLang['registration_valid_email'] = js_msg3_txt;
globalLang['registration_firstnm_required'] = js_validation_txt;
globalLang['registration_firstnm_contain_alphanm'] = js_msg4_txt;

globalLang['registration_lastnm_required'] = js_validation_txt;
globalLang['registration_lastnm_contain_alphanm'] = js_msg5_txt;

globalLang['registration_valid_nickname'] = js_msg6_txt;
globalLang['registration_sex_required'] = js_validation_txt;

globalLang['registration_email_required'] = js_validation_txt;
globalLang['registration_email_exists'] = js_msg7_txt;

globalLang['registration_pass_required'] = js_validation_txt;
globalLang['registration_pass_min_len'] = js_msg8_txt;

globalLang['registration_confirmpass_required'] = js_validation_txt;
globalLang['registration_phone_required'] = js_validation_txt;

globalLang['registration_birthday_required'] = js_validation_txt;
globalLang['registration_city_required'] = js_validation_txt;

globalLang['registration_terms_required'] = js_validation_txt;
globalLang['registration_privacy_required'] = js_validation_txt;

globalLang['registration_cookies_required'] = js_validation_txt;
globalLang['registration_sport_required'] = js_validation_txt;


function get_user_time_zone()
{
	 var visitortime = new Date();
	 var visitortimezone = -visitortime.getTimezoneOffset()/60;			
	 return visitortimezone;
}

function set_user_time_zone_to_element(eleid,tmzon)
{
	jQuery('#'+eleid).val(tmzon);
}

jQuery(document).ready(function(){	
	
	set_user_time_zone_to_element('tm_zone',get_user_time_zone())
	
	jQuery.validator.addMethod("alphaRegex", function(value, element) {
        return this.optional(element) || /^([^\x00-\x7F]|[a-zA-Z\-\s\'\.])+$/i.test(value);
    }, "");
    jQuery.validator.addMethod("alphaNicknameRegex", function(value, element) {
        return this.optional(element) || /^([^\x00-\x7F]|[a-zA-Z0-9\s\'\-\.])+$/i.test(value);
    }, "");
    
	jQuery.validator.addMethod("signup_password_match", function(vl, element) {

		if(jQuery('#password').val()!=vl)
		{
			return false;
		}
		else
			return true;
			
	}, globalLang['registration_confirmpass_same']); 
	
	jQuery.validator.addMethod("alphaNumRegex", function(value, element) {
        return this.optional(element) || /^([^\x00-\x7F]|[a-zA-Z0-9])+$/i.test(value);
    }, globalLang["registration_allowed_alphanumeric"]); 	 
	
	jQuery.validator.addMethod("emailValidRegex", function(value, element) {
        var pattern = new RegExp(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/);
        return pattern.test(value);
    }, globalLang['registration_valid_email']);	

    //signup page UI validation
	jQuery('#signupfrm').validate({
		ignore: [],
		rules: {
			name_f: {
				required: true,
				alphaRegex: true
			},
			name_l: {
				required: true,
				alphaRegex: true
			},
			nickname: {
				alphaNicknameRegex: true
			},
			sex: {
				required: true
			},
			email: {
				required: true,
				emailValidRegex: true,
				remote: {
                    url: tennisthor_wpadmin_url+'admin-post.php',
                    type: "post",
                    crossDomain:true,
                    data:{action:'tennisthor_email_valid_hook'}
                } 
			},
			password: {
				required: true,
				minlength: 8,
			},
			confirm_password: {
				required: true,
				signup_password_match: true
			},	
			birthday: {
				required: true,
			},
			phone: {
				required: true,
			},
			geoname_id: {
				required: true,
			},
			
			termscond: {
				required: true,
			},
			privacy: {
				required: true,
			},
			cookies: {
				required: true,
			},
			"usports[]": {
				required: true,
			},						
		},
		messages: {
			name_f: {
				required: globalLang['registration_firstnm_required'],
				alphaRegex: globalLang['registration_firstnm_contain_alphanm'],
			},
			name_l: {
				required: globalLang['registration_lastnm_required'],
				alphaRegex: globalLang['registration_lastnm_contain_alphanm'],
			},
			nickname: {
				alphaNicknameRegex: globalLang['registration_valid_nickname'],
			},
			sex: {
				required: globalLang['registration_sex_required'],
			},
			email: {
				required: globalLang['registration_email_required'],		
				remote: globalLang['registration_email_exists'],		
			},
			password: {
				required: globalLang['registration_pass_required'],
				minlength: globalLang['registration_pass_min_len'],
			},
			confirm_password: {
				required: globalLang['registration_confirmpass_required'],
			},			
			phone: {
				required: globalLang['registration_phone_required'],
			},
			birthday: {
				required: globalLang['registration_birthday_required'],
			},
			geoname_id: {
				required: globalLang['registration_city_required'],
			},
			
			termscond: {
				required: globalLang['registration_terms_required'],
			},
			privacy: {
				required: globalLang['registration_privacy_required'],
			},
			cookies: {
				required: globalLang['registration_cookies_required'],
			},
			"usports[]": {
				required: globalLang['registration_sport_required'],
			},					
		},    
		errorPlacement: function(error, element) {
			//alert(element.attr("name"))
			if(element.attr("name") == "termscond" || element.attr("name") == "privacy" || element.attr("name") == "cookies" || element.attr("name") == "reservation")
			{
				error.appendTo( element.parent().parent().parent().parent());
			}
			else if(element.attr("name") == "sex")
			{
				error.appendTo( element.parent().parent().parent().parent());
			}
			else if(element.attr("name") == "usports[]")
			{
				error.appendTo( element.parent().parent().parent().parent());
			}			
			else if(element.attr("name") == "birthday")
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
	
	var d = new Date(); 
	d.setDate(d.getDate() - 1);

    jQuery('.orderdate .date').datepicker({
    	startView: 2,
        todayBtn: "linked",
        format: 'dd-mm-yyyy',
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        endDate: d,
        autoclose: true
    });	
    
	jQuery(document).on("focusin", "#birthday", function() {
	   jQuery(this).prop('readonly', true);  
	});

	jQuery(document).on("focusout", "#birthday", function() {
	   jQuery(this).prop('readonly', false); 
	});    
	
    jQuery( ".typeahead_2" ).autocomplete({
		minLength: 3,
		delay: 1000,	     	
    	source: function (request, response) {
        	jQuery.ajax({
		        type: "POST",
		        url: tennisthor_wpadmin_url+'admin-post.php',
		        data: {action:'tennisthor_search_city_hook',geoname_country_id: jQuery('#geoname_country_id').val(), cityText: jQuery('.cityText').val()},
		        success: response,
		        dataType: 'json',
		        minLength: 3,
		        delay: 100,
		        crossDomain: true,
            });
    	},
    	select: function( event, ui ) {
            jQuery('#geoname_id').val(ui.item.id);		        	        
            jQuery('#country_name').val(ui.item.countryname);
			jQuery('#zip').val(jQuery.trim(ui.item.zipcode));
            		       
		}
		 ,			
	    response: function( e, ui ) {
	        jQuery('.city_no_suggestions').hide();
	        if(ui.content.length <= 0)
	        {
				jQuery('.city_no_suggestions').show();	
			}
	    }		
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
         return jQuery("<li></li>")
             .data("item.autocomplete", item)
             .append("<a>" + item.label + "</a>")
             .appendTo(ul);
     }; 	
     
		jQuery(".cityText").blur(function(){
		    jQuery('.city_no_suggestions').hide();
		});	     
     jQuery('#ui-id-1').addClass('f32');
});

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