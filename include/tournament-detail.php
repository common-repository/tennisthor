<?php

add_action('admin_post_nopriv_tennisthor_chat_hook','the_tennisthor_chat_hook_callback');
add_action('admin_post_tennisthor_chat_hook','the_tennisthor_chat_hook_callback');
if(!function_exists('the_tennisthor_chat_hook_callback'))
{
function the_tennisthor_chat_hook_callback()
{
	global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
	if(trim($tennis_club_id) == "")
		return false;

	$current_lang = '';
	if(isset($_COOKIE['tennis_lang']))
		$current_lang = $_COOKIE['tennis_lang'];	

	if(sanitize_text_field($_GET['type']) == 'getChats')
	{
		//chatroom_ajax_post
		$response = wp_remote_post( 'https://www.tennisthor.com/api/apitournament/chatroomajax', array(
		    'body'    => array(
		    		'tennisthor_action' => 'getChats',
		    		'tennisthor_room_id' => sanitize_text_field($_GET['tennisthor_room_id']),
		    		'tennisthor_tour_id' => sanitize_text_field($_GET['tennisthor_tour_id']),
		    		'tennisthor_lastID' => sanitize_text_field($_POST['lastID']),
		    	),
		    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
		) );	
		
		if($response['response']['code'] == 200)
		{			
			echo $response['body'];			
		}		
	}
	elseif($_GET['type'] == 'getUsers')
	{
		$response = wp_remote_post( 'https://www.tennisthor.com/api/apitournament/chatroomajax', array(
		    'body'    => array(
		    		'tennisthor_action' => 'getUsers',
		    		'tennisthor_room_id' => sanitize_text_field($_GET['tennisthor_room_id']),
		    		'tennisthor_tour_id' => sanitize_text_field($_GET['tennisthor_tour_id']),
		    	),
		    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
		) );	
		
		if($response['response']['code'] == 200)
		{			
			echo $response['body'];			
		}		
	}
}
}

add_action('admin_post_nopriv_tennisthor_sch_reload_courts_hook','the_sch_reload_courts_hook_callback');
add_action('admin_post_tennisthor_sch_reload_courts_hook','the_sch_reload_courts_hook_callback');
if(!function_exists('the_sch_reload_courts_hook_callback'))
{
function the_sch_reload_courts_hook_callback()
{
	global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
	if(trim($tennis_club_id) == "")
		return false;

	$current_lang = '';
	if(isset($_COOKIE['tennis_lang']))
		$current_lang = $_COOKIE['tennis_lang'];	
		
	$response = wp_remote_post( 'https://www.tennisthor.com/api/apitournament/reload_courts', array(
	    'body'    => array(
	    		'tennisthor_club_id' => sanitize_text_field($_POST['tennisthor_club_id']),
	    		'tennisthor_tour_id' => sanitize_text_field($_POST['tennisthor_tour_id']),
	    	),
	    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
	) );	

	if($response['response']['code'] == 200)
	{			
		echo $response['body'];			
	}
}
}


add_action('admin_post_nopriv_tennisthor_sch_reload_games_hook','the_sch_reload_games_hook_callback');
add_action('admin_post_tennisthor_sch_reload_games_hook','the_sch_reload_games_hook_callback');
if(!function_exists('the_sch_reload_games_hook_callback'))
{
function the_sch_reload_games_hook_callback()
{
	global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
	if(trim($tennis_club_id) == "")
		return false;

	$current_lang = '';
	if(isset($_COOKIE['tennis_lang']))
		$current_lang = $_COOKIE['tennis_lang'];	
	
	$body = array(
		'tennisthor_club_id' => sanitize_text_field($_POST['tennisthor_club_id']),
		'tennisthor_tour_id' => sanitize_text_field($_POST['tennisthor_tour_id']),
		'tennisthor_current_date' => sanitize_text_field($_POST['current_date']),
	);

	$response = wp_remote_post( 'https://www.tennisthor.com/api/apitournament/reload_games', array(
	    'body'    => $body,
	    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
	) );	
	if($response['response']['code'] == 200)
	{			
		echo $response['body'];			
	}
}
}


add_action('admin_post_nopriv_tennisthor_tournament_detail_hook','the_tennisthor_tournament_detail_hook_callback');
add_action('admin_post_tennisthor_tournament_detail_hook','the_tennisthor_tournament_detail_hook_callback');
if(!function_exists('the_tennisthor_tournament_detail_hook_callback'))
{
function the_tennisthor_tournament_detail_hook_callback()
{
	global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
	if(trim($tennis_club_id) == "")
		return false;
}
}

if (!is_admin()) 
{
add_shortcode( 'tennisthor_tournament_detail', 'tennisthor_tournament_detail_shortcode' );
if(!function_exists('tennisthor_tournament_detail_shortcode'))
{
function tennisthor_tournament_detail_shortcode() 
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
	
	wp_enqueue_style('tennis-tour1', plugins_url('../assets/css/dataTables/datatables.min.css',__FILE__));	
	wp_enqueue_script('tour-js-1', plugins_url('../assets/js/dataTables/datatables.min.js',__FILE__));		
	wp_enqueue_script('tour-js-2', plugins_url('../assets/js/tournament-list.js',__FILE__));
	wp_enqueue_style('tour-js-3', plugins_url('../assets/fullcalendar/fullcalendar.min.css',__FILE__));
	wp_enqueue_style('tour-js-4', plugins_url('../assets/fullcalendar/scheduler.min.css',__FILE__));	
	
	
	wp_enqueue_style('tennis-tour2', plugins_url('../assets/css/blueimp/css/blueimp-gallery.min.css',__FILE__));
	wp_enqueue_script('tour-js-3', plugins_url('../assets/js/blueimp/jquery.blueimp-gallery.min.js',__FILE__));
	
	wp_enqueue_script( 'moment' );
	wp_enqueue_script('tour-js-5', plugins_url('../assets/fullcalendar/fullcalendar.min.js',__FILE__));	
	wp_enqueue_script('tour-js-6', plugins_url('../assets/fullcalendar/scheduler.min.js',__FILE__));		
	
	
	
	$body = array();
	$body['tennisthor_tour_id'] = sanitize_text_field($_GET['tourid']);
	$tennisthor_tour_id = sanitize_text_field($_GET['tourid']);
	$active_tab = sanitize_text_field($_GET['tab']);
	
	$tabs = array('info','players_tab','group_tab','schema_tab','schedule_tab','points_tab','images_tab','chat_tab');	
	if(!in_array($active_tab, $tabs))
	{
		tnthor_redirect('/');
	}	
	
	$api_url = 'https://www.tennisthor.com/api/apitournament/tour_detail';
	if($active_tab == 'players_tab')
		$api_url = 'https://www.tennisthor.com/api/apitournament/players';
	elseif($active_tab == 'group_tab')
		$api_url = 'https://www.tennisthor.com/api/apitournament/groups';
	elseif($active_tab == 'schedule_tab')
		$api_url = 'https://www.tennisthor.com/api/apitournament/schedule';				
	elseif($active_tab == 'schema_tab')
		$api_url = 'https://www.tennisthor.com/api/apitournament/schema';			
	elseif($active_tab == 'points_tab')
		$api_url = 'https://www.tennisthor.com/api/apitournament/points';
	elseif($active_tab == 'images_tab')
		$api_url = 'https://www.tennisthor.com/api/apitournament/images';	
	elseif($active_tab == 'chat_tab')
		$api_url = 'https://www.tennisthor.com/api/apitournament/chatroom';			
		
	$body = recursive_sanitize_text_field($body);
	$response = wp_remote_post( $api_url, array(
	    'body'    => $body,
	    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
	));	

	if($response['response']['code'] == 200)
	{			
		$body = json_decode($response['body'],true);		
	}	
	else
	{
		tnthor_redirect('/');
	}
	
	$page_option = get_option('tennisthor_tournament_detail_page');
	$page = get_page($page_option);
	$detail_page_link = get_page_link($page);	
	
	$detail_link = $detail_page_link.'?tab=info&tourid='.$tennisthor_tour_id;
	$players_link = $detail_page_link.'?tab=players_tab&tourid='.$tennisthor_tour_id;
	$groups_link = $detail_page_link.'?tab=group_tab&tourid='.$tennisthor_tour_id;
	$schema_link = $detail_page_link.'?tab=schema_tab&tourid='.$tennisthor_tour_id;
	$schedule_link = $detail_page_link.'?tab=schedule_tab&tourid='.$tennisthor_tour_id;
	$points_link = $detail_page_link.'?tab=points_tab&tourid='.$tennisthor_tour_id;
	$images_link = $detail_page_link.'?tab=images_tab&tourid='.$tennisthor_tour_id;
	$chat_link = $detail_page_link.'?tab=chat_tab&tourid='.$tennisthor_tour_id;
	
	$show_group = false;
	$show_schema = false;
	
	if($body['tour_detail'][0]['tour_type_code'] == "group_and_schema"):
		$show_group = true;
		$show_schema = true;
	elseif($body['tour_detail'][0]['tour_type_code'] == "group"):
		$show_group = true;
		$show_schema = false;							
	elseif($body['tour_detail'][0]['tour_type_code'] == "schema"):
		$show_group = false;
		$show_schema = true;														
	endif;	
	?>

<div class="tabs">
  <input name="tabs" type="radio" id="info" <?php if($active_tab == "info"){ ?>checked="checked"<?php }?> class="input"/>
  <a for="info" class="label" href="<?php echo $detail_link;?>"><?php echo $body['tab_txt1'];?></a>
  <div class="panel">
	  	<?php if($active_tab == "info"){?>
			<?php require_once 'tour_detail/info.php';?>
		<?php }?>
  </div>
  
  <input name="tabs" type="radio" id="players_tab" <?php if($active_tab == "players_tab"){ ?>checked="checked"<?php }?> class="input"/>
  <a for="players_tab" class="label" href="<?php echo $players_link;?>"><?php echo $body['tab_txt2'];?></a>
  <div class="panel">
	  	<?php if($active_tab == "players_tab"){?>
			<?php require_once 'tour_detail/players.php';?>
		<?php }?>
  </div>
  
  <?php if($show_group){ ?>
  <input name="tabs" type="radio" id="group_tab" <?php if($active_tab == "group_tab"){ ?>checked="checked"<?php }?> class="input"/>
  <a for="group_tab" class="label" href="<?php echo $groups_link;?>"><?php echo $body['tab_txt3'];?></a>
  <div class="panel">
	  	<?php if($active_tab == "group_tab"){?>
			<?php require_once 'tour_detail/groups.php';?>
		<?php }?>
  </div>  
  <?php }?>
  
  <?php if($show_schema){ ?>
  <input name="tabs" type="radio" id="schema_tab" <?php if($active_tab == "schema_tab"){ ?>checked="checked"<?php }?> class="input"/>
  <a for="schema_tab" class="label" href="<?php echo $schema_link;?>"><?php echo $body['tab_txt4'];?></a>
  <div class="panel">
	  	<?php if($active_tab == "schema_tab"){?>
			<?php require_once 'tour_detail/schema.php';?>
		<?php }?>
  </div> 
  <?php }?>
  
  <input name="tabs" type="radio" id="schedule_tab" <?php if($active_tab == "schedule_tab"){ ?>checked="checked"<?php }?> class="input"/>
  <a for="schedule_tab" class="label" href="<?php echo $schedule_link;?>"><?php echo $body['tab_txt5'];?></a>
  <div class="panel">
	  	<?php if($active_tab == "schedule_tab"){?>
			<?php require_once 'tour_detail/schedule.php';?>
		<?php }?>
  </div>  
  
  <input name="tabs" type="radio" id="points_tab" <?php if($active_tab == "points_tab"){ ?>checked="checked"<?php }?> class="input"/>
  <a for="points_tab" class="label" href="<?php echo $points_link;?>"><?php echo $body['tab_txt6'];?></a>
  <div class="panel">
	  	<?php if($active_tab == "points_tab"){?>
			<?php require_once 'tour_detail/points.php';?>
		<?php }?>
  </div> 
  
  <input name="tabs" type="radio" id="images_tab" <?php if($active_tab == "images_tab"){ ?>checked="checked"<?php }?> class="input"/>
  <a for="images_tab" class="label" href="<?php echo $images_link;?>"><?php echo $body['tab_txt7'];?></a>
  <div class="panel">
	  	<?php if($active_tab == "images_tab"){?>
			<?php require_once 'tour_detail/gallery.php';?>
		<?php }?>
  </div>
  
  <input name="tabs" type="radio" id="chat_tab" <?php if($active_tab == "chat_tab"){ ?>checked="checked"<?php }?> class="input"/>
  <a for="chat_tab" class="label" href="<?php echo $chat_link;?>"><?php echo $body['tab_txt8'];?></a>
  <div class="panel">
	  	<?php if($active_tab == "chat_tab"){?>
			<?php require_once 'tour_detail/chatroom.php';?>
		<?php }?>
  </div>  


</div>

	
<script>
	var tour_detail_page = '1';
	var tennisthor_wpadmin_url = '<?php echo TENNISTHOR_ADMIN_URL;?>';
</script>	
	<?php
}

}
}
?>