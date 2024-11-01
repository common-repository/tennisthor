
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
	set_user_time_zone_to_element('tennisthor_tmzone',get_user_time_zone())
});