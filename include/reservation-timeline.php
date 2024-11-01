<?php
// Register a new shortcode: 


add_action('admin_post_nopriv_tennisthor_get_booking_url_hook','tennisthor_get_booking_url_hook_callback');
add_action('admin_post_tennisthor_get_booking_url_hook','tennisthor_get_booking_url_hook_callback');
if(!function_exists('tennisthor_get_booking_url_hook_callback'))
{	
function tennisthor_get_booking_url_hook_callback()
{
	global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
	if(trim($tennis_club_id) == "")
		return false;

	$current_lang = '';
	if(isset($_COOKIE['tennis_lang']))
		$current_lang = $_COOKIE['tennis_lang'];

	$body = array(
	    		'tennisthor_booking_club_id' => $_POST['booking_club_id'],
	    		'tennisthor_booking_sport_id' => $_POST['booking_sport_id'],
	    		'tennisthor_booking_court_id' => $_POST['booking_court_id'],
	    		'tennisthor_booking_start_time' => $_POST['booking_start_time'],
	    		'tennisthor_booking_end_time' => $_POST['booking_end_time'],
	    		'tennisthor_booking_date' => $_POST['booking_date'],
	    	);
	
	if(isset($_SESSION['tennithor_user_']))
	{
		$tennisthor_user_id = tennisthor_encrypt_decrypt('encrypt', $_SESSION['tennithor_user_']['uid']);
		$body['tennisthor_user_id'] = $tennisthor_user_id;
	}			
	
	$body = recursive_sanitize_text_field($body);
	$response = wp_remote_post( 'https://www.tennisthor.com/api/apireservation/get_booking_url', array(
	    'body'    => $body,
	    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
	) );	

	if($response['response']['code'] == 200)
	{			
		echo $response['body'];		
	}
}
}

add_action('admin_post_nopriv_tennisthor_load_timeline_calendar_hook','tennisthor_load_timeline_calendar_hook_callback');
add_action('admin_post_tennisthor_load_timeline_calendar_hook','tennisthor_load_timeline_calendar_hook_callback');
if(!function_exists('tennisthor_load_timeline_calendar_hook_callback'))
{	
function tennisthor_load_timeline_calendar_hook_callback()
{
	global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
	if(trim($tennis_club_id) == "")
		return false;

	$current_lang = '';
	if(isset($_COOKIE['tennis_lang']))
		$current_lang = $_COOKIE['tennis_lang'];	
		
	$response = wp_remote_post( 'https://www.tennisthor.com/api/apireservation/get_timeline_days', array(
	    'body'    => array(
	    		'tennisthor_type' => sanitize_text_field($_POST['type']),
	    		'tennisthor_first_date' => sanitize_text_field($_POST['first_date']),
	    		'tennisthor_last_date' => sanitize_text_field($_POST['last_date']),
	    	),
	    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
	) );	

	if($response['response']['code'] == 200)
	{			
		$body = json_decode($response['body'], true);
		echo $body;		
	}
}
}

add_action('admin_post_nopriv_tennisthor_res_reload_courts_hook','the_res_reload_courts_hook_callback');
add_action('admin_post_tennisthor_res_reload_courts_hook','the_res_reload_courts_hook_callback');
if(!function_exists('the_res_reload_courts_hook_callback'))
{	
function the_res_reload_courts_hook_callback()
{
	global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
	if(trim($tennis_club_id) == "")
		return false;

	$current_lang = '';
	if(isset($_COOKIE['tennis_lang']))
		$current_lang = $_COOKIE['tennis_lang'];	
		
	$response = wp_remote_post( 'https://www.tennisthor.com/api/apireservation/reload_courts', array(
	    'body'    => array(
	    		'tennisthor_club_id' => sanitize_text_field($_POST['club_id']),
	    		'tennisthor_sport_id' => sanitize_text_field($_POST['sport_id']),
	    		'tennisthor_current_date' => sanitize_text_field($_POST['current_date']),
	    	),
	    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
	) );	

	if($response['response']['code'] == 200)
	{			
		$body = json_decode($response['body'], true);
		echo $body;			
	}
}
}

add_action('admin_post_nopriv_tennisthor_res_reload_events_hook','the_res_reload_events_hook_callback');
add_action('admin_post_tennisthor_res_reload_events_hook','the_res_reload_events_hook_callback');
if(!function_exists('the_res_reload_events_hook_callback'))
{
function the_res_reload_events_hook_callback()
{
	global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
	if(trim($tennis_club_id) == "")
		return false;

	$current_lang = '';
	if(isset($_COOKIE['tennis_lang']))
		$current_lang = $_COOKIE['tennis_lang'];	
	
	$body = array(
		'tennisthor_club_id' => $_POST['club_id'],
		'tennisthor_sport_id' => $_POST['sport_id'],
		'tennisthor_current_date' => $_POST['current_date'],
		'tennisthor_mode' => $_POST['mode'],
		'tennisthor_resource_array' => $_POST['resource_array'],
	);
	if(isset($_SESSION['tennithor_user_']))
	{
		$tennisthor_user_id = tennisthor_encrypt_decrypt('encrypt', $_SESSION['tennithor_user_']['uid']);
		$body['tennisthor_user_id'] = $tennisthor_user_id;
	}	

	$body = recursive_sanitize_text_field($body);
	$response = wp_remote_post( 'https://www.tennisthor.com/api/apireservation/reload_events', array(
	    'body'    => $body,
	    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
	) );	

	if($response['response']['code'] == 200)
	{			
		$body = json_decode($response['body'], true);
		echo $body;			
	}
}
}

if (!is_admin()) 
{
	add_shortcode( 'tennisthor_reservation_timeline', 'tennisthor_reservation_timeline_shortcode' );
	if(!function_exists('tennisthor_reservation_timeline_shortcode'))
	{
	function tennisthor_reservation_timeline_shortcode() 
	{
		if(strpos($_SERVER['REQUEST_URI'], 'wp-json/') != "")
		{
			return '';
		}	
		global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
		if(trim($tennis_club_id) == "")
			return false;

		$current_lang = '';
		if(isset($_COOKIE['tennis_lang']))
			$current_lang = $_COOKIE['tennis_lang'];		
		
		wp_enqueue_style('tennis-timeline1', plugins_url('../assets/css/slick/slick.css',__FILE__));
		wp_enqueue_style('tennis-timeline2', plugins_url('../assets/css/slick/slick-theme.css',__FILE__));
		wp_enqueue_style('tennis-timeline3', plugins_url('../assets/fullcalendar/fullcalendar.min.css',__FILE__));
		wp_enqueue_style('tennis-timeline5', plugins_url('../assets/fullcalendar/scheduler.min.css',__FILE__));		
		wp_enqueue_script( 'moment' );
		wp_enqueue_script('reservation-js-2', plugins_url('../assets/fullcalendar/fullcalendar.min.js',__FILE__));	
		wp_enqueue_script('reservation-js-3', plugins_url('../assets/fullcalendar/scheduler.min.js',__FILE__));	
		wp_enqueue_script('reservation-js-4', plugins_url('../assets/js/reservation.js',__FILE__));	
		
		$response = wp_remote_post( 'https://www.tennisthor.com/api/apireservation/index', array(
		    'body'    => array(),
		    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
		) );	
		
		$body = "";
		if($response['response']['code'] == 200)
		{			
			$body = json_decode($response['body'], true);			
		}
		else
		{
			tnthor_redirect('/');
		}	

		$user_sport_id = "";
		if(isset($_GET['sport_id']))
		{
			$user_sport_id = sanitize_text_field($_GET['sport_id']);
			$sport_id_flag = true;
			if(trim($user_sport_id) != "")
			{
				foreach($body['sports'] as $sport)
				{
					if($user_sport_id == $sport['sport_id'])
					{
						$sport_id_flag = false;	
						break;
					}				
				}	

				if($sport_id_flag)		
				{
					tnthor_redirect('/');
				}
			}			
		}


		
		?>
		<script>
			var tennis_thor_domain = '<?php echo TENNISTHOR_DOMAIN;?>';
		</script>
		<?php	
		/*echo "<pre>";
		print_r($body);
		echo "</pre>";*/
		?>
		<span class="loader" id="overlay" style="display:none;"><img src="<?php echo plugins_url('/tennisthor/assets/images/ajax-loader.gif'); ?>" /></span>
		<div style="float: right;" class="form-group">
			<select name="tennis_lang" id="tennis_lang" class="form-control" onchange="change_language(this.value)">
				<?php foreach($body['languages'] as $lang){ 

						$selected = '';
						if($current_lang != "")
						{
							if($current_lang == $lang['lang'])
							{
								$selected = 'selected=""';
							}													
						}
						else
						{
							if($body['default_lang_code'] == $lang['lang'])
							{
								$selected = 'selected=""';
							}							
						}
					?>
					<option value="<?php echo $lang['lang'];?>" <?php echo $selected;?>><?php echo $lang['lang_name'];?></option>
				<?php }?>
			</select>
		</div>
		
		<?php echo $body['time_slide_html'];?>
		<div class="m-t clearfix"></div>	
	    <div class="form-group">
	    	<div class="col-sm-12">
	    		<table>
	    			<tr>
	    				<td>
							<select class="form-control select2_demo_3" name="admin_timeline_mode" id="admin_timeline_mode" onchange="get_calendar_mode_change()">
		                		<?php foreach($body['timeline_modes'] as $key => $mode):
		                			$checked = '';

										if(isset($_GET['print']) && isset($_GET['admin_timeline_mode']))
										{
											if($_GET['admin_timeline_mode'] == tennisthor_encrypt_decrypt('encrypt', $key))
												$checked = 'selected=""';
										}				                			
		                		?>
		                			<option value="<?php echo tennisthor_encrypt_decrypt('encrypt', $key);?>" <?php echo $checked;?>><?php echo $mode;?></option>
		                		<?php endforeach;?>
				        	</select>		                            					
	    				</td>
	    				<td>&nbsp;</td>
	    				<td>
				        	<select class="form-control select2_demo_3" name="sport_id" id="sport_id" onchange="get_calendar_sport_change('<?php echo $body['club_id'];?>',this)">
	                    		<?php foreach($body['sports'] as $sport):
	                    				$checked = '';	
	                    				if(isset($user_sport_id))
	                    				{
											if($user_sport_id == $sport['sport_id'])
												$checked = 'selected=""';	
										}
										else
										{
											if($sport['default_option'] == "1")
												$checked = 'selected=""';	
										}
	                    		?>
	                    			<option value="<?php echo $sport['sport_id'];?>" <?php echo $checked;?>><?php echo $sport['sport_name'];?></option>
	                    		<?php endforeach;?>
				        	</select>	                            					
	    				</td>
	    				<td>&nbsp;</td>
						<td class="weather_con_text">
	    					<label class="control-label"><?php echo $body['wth_cond_txt'];?>:</label>
	    					<?php echo $body['club_data'][0]['wheather_name'];?>
	    				</td>	                            				
	    			</tr>
	    		</table>
	    	</div>
	        <div class="col-sm-5">

	        </div>
	    </div>                        

	<div class="m-t clearfix"></div>

	<div id='calendar'></div>
	<div id='calendar_show'></div>
	<div class="m-t clearfix"></div>
	<div class="m-t clearfix"></div>

	<div class="form-group">
		<div class="col-sm-4">
			<div class="calendar_agenda calendar_agenda_white"></div> <?php echo $body['timeline_status_text1'];?>
			<div class="mtop clearfix"></div>
			<div class="calendar_agenda calendar_agenda_orange"></div> <?php echo $body['timeline_status_text2'];?>

			<div class="mtop clearfix"></div>
			<div class="calendar_agenda calendar_agenda_magenta"></div> <?php echo $body['timeline_status_text3'];?>									 

			<div class="mtop clearfix"></div>

			<div class="calendar_agenda calendar_agenda_blue"></div> <?php echo $body['timeline_status_text4'];?>
			<div class="mtop clearfix"></div>									 
			<div class="calendar_agenda calendar_agenda_gray"></div> <?php echo $body['timeline_status_text5'];?>

			<div class="mtop clearfix"></div>									 
			<div class="calendar_agenda calendar_agenda_par_blue"></div> <?php echo $body['timeline_status_text6'];?>										 
		</div>
	</div>	

	<input type="hidden" name="club_id" id="club_id" value="<?php echo $body['club_id'];?>"/>

	<script>
		var load_reservation_flag = true;
		var booking_time_slots;
		var calendar_current_date = '<?php echo date("Y-m-d", strtotime($body["get_now_date"]));?>';
		var slotLabelFormat_ = '<?php echo $body['slotLabelFormat'];?>';
		var min_time = '<?php echo $body['min_max_time']['min_time'];?>';
		var max_time = '<?php echo $body['min_max_time']['max_time'];?>';		
		var lighting_text = '<?php echo $body['lighting_txt'];?>';
		var heating_text = '<?php echo $body['heating_txt'];?>';
		var timing_txt = '<?php echo $body['timing_txt'];?>';
		var price_txt = '<?php echo $body['price_txt'];?>';
		var ok_txt = '<?php echo $body['ok_txt'];?>';
		var f = true;
		var f_enc_club_id = '<?php echo tennisthor_encrypt_decrypt("encrypt",$body['club_id']);?>';
		var enc_club_id = '<?php echo $body['enc_club_id']; ?>';
		
		var slot_duration_var = '<?php echo $body['slot_duration_var'];?>';		
		var col_setting = true;
		<?php if($body['col_setting'] == "1"):?>
			var col_setting = false;
		<?php endif; ?>	
		
		var locale_code = '<?php echo $body['lang_code'];?>';	
		var tennisthor_wpadmin_url = '<?php echo TENNISTHOR_ADMIN_URL;?>';
	</script>
		
		<?php
	}

	}

}
?>