<?php
// create custom plugin settings menu
add_action('admin_menu', 'tennisthor_plugin_admin_menu');

if(!function_exists('tennisthor_plugin_admin_menu'))
{	
function tennisthor_plugin_admin_menu()
{
	global $enc_front_club_id;
	$token = get_option('tennisthor_admin_api_token');
	
	add_menu_page('TennisThor', 'TennisThor', 'manage_options', 'tennisthor_manage', '', plugins_url('/tennisthor/assets/images/logo_wp.png'));
		
	add_submenu_page( 'tennisthor_manage', 'ADMIN', '<strong style="font-weight: bold;">ADMIN</strong>', 'manage_options', '#');	
	add_submenu_page( 'tennisthor_manage', 'Configuration', 'Configuration', 'manage_options', 'tennisthor-setting', 'tennisthor_setting');
	$front_club_id = get_option('tennisthor_frontend_club_id');
	if($front_club_id > 0)
	{
		$enc_front_club_id = tennisthor_encrypt_decrypt('encrypt', $front_club_id);
		add_submenu_page( 'tennisthor_manage', 'Messages', 'Messages', 'manage_options', 'tennisthor-messages', 'tennisthor_messages');	
		add_submenu_page( 'tennisthor_manage', 'Reservation', 'Reservation', 'manage_options', 'tennisthor-reservation', 'tennisthor_my_reservation');	
		add_submenu_page( 'tennisthor_manage', 'Clients', 'Clients', 'manage_options', 'tennisthor-client', 'tennisthor_client');	
		add_submenu_page( 'tennisthor_manage', 'Tournaments', 'Tournaments', 'manage_options', 'tennisthor-tournaments', 'tennisthor_tournaments');	
		add_submenu_page( 'tennisthor_manage', 'Cards', 'Cards', 'manage_options', 'tennisthor-cards', 'tennisthor_cards');	
		add_submenu_page( 'tennisthor_manage', 'Ratings', 'Ratings', 'manage_options', 'tennisthor-ratings', 'tennisthor_ratings');	
		
		add_submenu_page( 'tennisthor_manage', 'Settings', '<strong style="font-weight: bold;">Settings</strong>', 'manage_options', '#');
		
		add_submenu_page( 'tennisthor_manage', 'Courts', 'Courts', 'manage_options', 'tennisthor-courts', 'tennisthor_courts');	
		add_submenu_page( 'tennisthor_manage', 'Staff', 'Staff', 'manage_options', 'tennisthor-staff', 'tennisthor_staff');	
		add_submenu_page( 'tennisthor_manage', 'Club Setting', 'Club Setting', 'manage_options', 'tennisthor-clubset', 'tennisthor_clubset');	
		add_submenu_page( 'tennisthor_manage', 'Edit', 'Edit', 'manage_options', 'tennisthor-edit', 'tennisthor_edit');	
		add_submenu_page( 'tennisthor_manage', 'Club website', 'Club website', 'manage_options', 'tennisthor-clubsite', 'tennisthor_clubsite');	
		add_submenu_page( 'tennisthor_manage', 'Events', 'Events', 'manage_options', 'tennisthor-events', 'tennisthor_events');	
		add_submenu_page( 'tennisthor_manage', 'Reports', 'Reports', 'manage_options', 'tennisthor-reports', 'tennisthor_reports');	
	}

	if(trim($token) == "")
	{
		add_submenu_page( 'tennisthor_manage', 'Registration', 'Registration', 'manage_options', 'tennisthor-registration', 'tennisthor_registration');	
	}

	//add_submenu_page( 'tennisthor_manage', 'Sports Club', 'Sports Club', 'manage_options', 'sports-club', 'tennisthor_sports_club');
	//add_submenu_page( 'tennisthor_manage', 'Sports League', 'Sports League', 'manage_options', 'tennisthor-league', 'tennisthor_league');
	
	/*add_submenu_page( 'tennisthor_manage', 'USER', '<strong style="font-weight: bold;">USER</strong>', 'manage_options', '#');	
	
	if(trim($token) == "")
	{
		add_submenu_page( 'tennisthor_manage', 'Registration', 'Registration', 'manage_options', 'tennisthor-registration', 'tennisthor_registration');	
	}
	
	add_submenu_page( 'tennisthor_manage', 'Dashboard', 'Dashboard', 'manage_options', 'tennisthor-dashboard', 'tennisthor_dashboard');
	add_submenu_page( 'tennisthor_manage', 'My Games ', 'My Games ', 'manage_options', 'tennisthor-my-games', 'tennisthor_my_games');
	add_submenu_page( 'tennisthor_manage', 'My reservation ', 'My reservation ', 'manage_options', 'tennisthor-my-reservation', 'tennisthor_my_reservation');
	add_submenu_page( 'tennisthor_manage', 'Thor Power Rating', 'Thor Power Rating', 'manage_options', 'tennisthor-thor-power-rating', 'tennisthor_thor_power_rating');
	add_submenu_page( 'tennisthor_manage', 'Messages', 'Messages', 'manage_options', 'tennisthor-u-messages', 'tennisthor_u_messages');
	add_submenu_page( 'tennisthor_manage', 'Sports Club', 'Sports Club', 'manage_options', 'tennisthor-u-sports-club', 'tennisthor_u_sports_club');
	add_submenu_page( 'tennisthor_manage', 'Sports League', 'Sports League', 'manage_options', 'tennisthor-sports-league', 'tennisthor_sports_league');
	add_submenu_page( 'tennisthor_manage', 'Tournaments', 'Tournaments', 'manage_options', 'tennisthor-tournaments', 'tennisthor_tournaments');
	add_submenu_page( 'tennisthor_manage', 'My Teams', 'My Teams', 'manage_options', 'tennisthor-my-teams', 'tennisthor_my_teams');
	add_submenu_page( 'tennisthor_manage', 'Events', 'Events', 'manage_options', 'tennisthor-events', 'tennisthor_events');
	add_submenu_page( 'tennisthor_manage', 'My Family', 'My Family', 'manage_options', 'tennisthor-myfamily', 'tennisthor_myfamily');
	add_submenu_page( 'tennisthor_manage', 'I am Coach', 'I am Coach', 'manage_options', 'tennisthor-iamcoach', 'tennisthor_iamcoach');
	
	add_submenu_page( 'tennisthor_manage', 'Edit Profile', 'Edit Profile', 'manage_options', 'tennisthor-edit-profile', 'tennisthor_edit_profile');
	add_submenu_page( 'tennisthor_manage', 'Edit Rights', 'Edit Rights', 'manage_options', 'tennisthor-edit-right', 'tennisthor_edit_right');
	add_submenu_page( 'tennisthor_manage', 'Edit Password', 'Edit Password', 'manage_options', 'tennisthor-edit-password', 'tennisthor_edit_password');
	add_submenu_page( 'tennisthor_manage', 'Delete Profile', 'Delete Profile', 'manage_options', 'tennisthor-delete-password', 'tennisthor_delete_password');*/

	remove_submenu_page('tennisthor_manage','tennisthor_manage');
}
}

if(!function_exists('tennisthor_setting'))
{
function tennisthor_setting()
{
	if(isset($_POST['submit'])) //save apikey
	{

		$response = wp_remote_post( 'https://www.tennisthor.com/api/user/auth', array(
		    'body'    => $_POST,
		    'headers' => array(),
		) );
		
		if($response['response']['code'] == 200)
		{			
			$body = json_decode($response['body'], true);
			
			update_option('tennisthor_admin_api_token', $body['token']);
			update_option('tennisthor_admin_user_details', $response['body'],false);
			
			$_SESSION['success_message'] = $body['message'];
			tnthor_admin_redirect('page='.$_GET['page']);
		}
		else
		{
			$body = json_decode($response['body'], true);
			$_SESSION['error_message'] = $body['message'];
		}
	}

	$front_club_id = get_option('tennisthor_frontend_club_id');
	$token = get_option('tennisthor_admin_api_token');	
	if(isset($_POST['club_setting'])) //save club
	{
		update_option('tennisthor_frontend_club_id', $_POST['tennisthor_club']);

		$enc_tennis_club_id = tennisthor_encrypt_decrypt('encrypt',$_POST['tennisthor_club']);
		$response = wp_remote_post( 'https://www.tennisthor.com/api/club/saveclubwpinfo', array(
		    'body'    => array(
		    		'enc_tennis_club_id' => $enc_tennis_club_id,
		    		'wp_url' => site_url(),
		    	),			
		    'headers' => array('USER-TOKEN' => $token),
		) );

		$_SESSION['success_message'] = "Club saved for frontend.";
		tnthor_admin_redirect('page='.$_GET['page']);		
	}
	?>
	
	<?php echo tnthor_display_success();?>


	<h1>Setup TennisThor plugin</h1>
		<?php if(trim($token) != ''){?>
			<strike><b>1. Register as a user at TennisThor.com <a href="?page=tennisthor-registration" target="_blank">HERE</a></b></strike>
		<?php }else{ ?>
			<b>1. Register as a user at TennisThor.com <a href="?page=tennisthor-registration" target="_blank">HERE</a></b>
		<?php } ?>        
		<br />
        <b>2. Create a Sports Club <a href="https://www.tennisthor.com/sportsclub/create" target="_blank">HERE</a></b><br />
        <b>3. Follow the steps on the site to create your facilities/courts and usage prices</b><br />
        <b>4. Enter TennisThor.com credential to get your clubs lists</b><br />
	

	<form method="post" action="" novalidate="novalidate">
		<?php settings_fields( 'tennisthor-setting' ); ?>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="tennisthor_email">Email</label></th>
					<td><input name="tennisthor_email" type="text" id="tennisthor_email" value="" class="regular-text"></td>
				</tr>

				<tr>
					<th scope="row"><label for="tennisthor_password">Password</label></th>
					<td><input name="tennisthor_password" type="password" id="tennisthor_password" value="" class="regular-text"></td>
				</tr>
				
				<?php 
					
					/*if(trim($token) != "")
					{
						?>
							<tr>
								<th scope="row"><label for="">API key:</label></th>
								<td><?php echo $token;?></td>
							</tr>			
						<?php
					}*/
				?>				
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Login in the club"></p>
	</form>

	
	<br />
	<br />
	<?php if(trim($token) != ""){ 
		$response = wp_remote_post( 'https://www.tennisthor.com/api/club/index', array(
		    'headers' => array('USER-TOKEN' => $token),
		) );	
		
		$club_lists = array();
		if($response['response']['code'] == 200)
		{			
			$body = json_decode($response['body'], true);
			$club_lists = $body['listrows'];
		}
		else
		{
			$body = json_decode($response['body'], true);
			$_SESSION['error_message'] = $body['message'];
		}		
		//https://www.tennisthor.com/api/club/index
	?>
	<b>5. Select which sports club you want to use</b><br />
		<form method="post" action="" >

			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="tennisthor_club">Clubs</label></th>
						<td>
							
							<select name="tennisthor_club" required>
								<option value="">Choose</option>
								<?php foreach($club_lists as $club_list){?>
									<option value="<?php echo $club_list['club_id'];?>" <?php if($front_club_id == $club_list['club_id']){ echo 'selected=""';} ?>><?php echo $club_list['club_name'];?></option>
								<?php }?>
							</select>
						</td>
					</tr>		
				</tbody>
			</table>
			<p class="submit"><input type="submit" name="club_setting" id="club_setting" class="button button-primary" value="SAVE"></p>
		</form>	
	<?php }?>

<br>
<b>6. Add the booking and registration links to your club</b><br />
<img src="<?php echo plugins_url('/tennisthor/assets/images/screen_add_menu1.png');?>">
<img src="<?php echo plugins_url('/tennisthor/assets/images/screen_add_menu2.png');?>">
<br>
<b>7. You are ready, you can tell your customers that they can book courts online</b><br />




<br>
	<h1>Ask Questions</h1>
If you have any regarding plugin functionality or any design issue please contact us at: <a target="_blank" href="https://www.tennisthor.com/en/contactus">https://www.tennisthor.com/en/contactus</a>


<br>
<h1>NOTE</h1>
- If you use server based caching such as nginx and Varnish, then this plugin might be conflict with server based cache services. If you want it we can customize it for you please contact at: https://www.tennisthor.com/en/contactus





	<?php
}
}

function tennisthor_messages()
{
	global $enc_front_club_id;
	tnthor_display_iframe('https://www.tennisthor.com/messages/lists/'.$enc_front_club_id, 'Messages');
}

function tennisthor_my_reservation()
{
	global $enc_front_club_id;
	tnthor_display_iframe('https://www.tennisthor.com/reservationadmin/index/'.$enc_front_club_id, 'Reservation');
}

function tennisthor_client()
{
	global $enc_front_club_id;
	$front_club_id = get_option('tennisthor_frontend_club_id');
	tnthor_display_iframe('https://www.tennisthor.com/sportsclub/clients/'.$front_club_id, 'Clients');
}

function tennisthor_tournaments()
{
	global $enc_front_club_id;
	$front_club_id = get_option('tennisthor_frontend_club_id');	
	tnthor_display_iframe('https://www.tennisthor.com/'.$front_club_id.'/tournament', 'Tournaments');
}

function tennisthor_cards()
{
	global $enc_front_club_id;
	tnthor_display_iframe('https://www.tennisthor.com/'.$enc_front_club_id.'/carts', 'Cards');
}

function tennisthor_ratings()
{
	global $enc_front_club_id;
	tnthor_display_iframe('https://www.tennisthor.com/rating/index/'.$enc_front_club_id, 'Ratings');
}


function tennisthor_courts()
{
	global $enc_front_club_id;
	tnthor_display_iframe('https://www.tennisthor.com/'.$enc_front_club_id.'/courts', 'Courts');
}

function tennisthor_staff()
{
	global $enc_front_club_id;
	$front_club_id = get_option('tennisthor_frontend_club_id');	
	tnthor_display_iframe('https://www.tennisthor.com/sportsclub/ourstaff/'.$front_club_id, 'Staff');
}

function tennisthor_clubset()
{
	global $enc_front_club_id;
	tnthor_display_iframe('https://www.tennisthor.com/sportsclub/clubsettings/'.$enc_front_club_id, 'Club Settings');
}

function tennisthor_edit()
{
	global $enc_front_club_id;
	$front_club_id = get_option('tennisthor_frontend_club_id');	
	tnthor_display_iframe('https://www.tennisthor.com/sportsclub/edit/'.$front_club_id, 'Edit');
}

function tennisthor_clubsite()
{
	global $enc_front_club_id;
	tnthor_display_iframe('https://www.tennisthor.com/clubwebsite/index/'.$enc_front_club_id, 'Club website');
}

function tennisthor_events()
{
	global $enc_front_club_id;
	tnthor_display_iframe('https://www.tennisthor.com/events/index/'.$enc_front_club_id, 'Events');
}

function tennisthor_reports()
{
	global $enc_front_club_id;
	tnthor_display_iframe('https://www.tennisthor.com/clubreport/index/'.$enc_front_club_id, 'Reports');
}







function tennisthor_sports_club()
{
	tnthor_display_iframe('https://www.tennisthor.com/sportsclub', 'Sports Club');
}

function tennisthor_league()
{
	tnthor_display_iframe('https://www.tennisthor.com/leages/index', 'Sports League');
}


//USER
function tennisthor_registration()
{
	tnthor_display_iframe('https://www.tennisthor.com/en/signup', 'Tennis Thor Registration', 'height:1800px;');
}

function tennisthor_dashboard()
{
	tnthor_display_iframe('https://www.tennisthor.com', 'Dashboard');
}

function tennisthor_my_games()
{
	tnthor_display_iframe('https://www.tennisthor.com/usergame/index', 'My Games');
}



function tennisthor_thor_power_rating()
{
	tnthor_display_iframe('https://www.tennisthor.com/rating/thor_ratings', 'Thor Power Rating');
}

function tennisthor_u_messages()
{
	tnthor_display_iframe('https://www.tennisthor.com/messages/index', 'Messages');
}

function tennisthor_u_sports_club()
{
	tnthor_display_iframe('https://www.tennisthor.com/clubs', 'Sports Club');
}

function tennisthor_sports_league()
{
	tnthor_display_iframe('https://www.tennisthor.com/league', 'Sports League');
}


function tennisthor_my_teams()
{
	tnthor_display_iframe('https://www.tennisthor.com/teams', 'My Teams');
}


function tennisthor_myfamily()
{
	tnthor_display_iframe('https://www.tennisthor.com/myfamily', 'My Family');
}

function tennisthor_iamcoach()
{
	tnthor_display_iframe('https://www.tennisthor.com/coach/index', 'I am Coach');
}

function tennisthor_edit_profile()
{
	tnthor_display_iframe('https://www.tennisthor.com/profile', 'Edit Profile');
}

function tennisthor_edit_right()
{
	tnthor_display_iframe('https://www.tennisthor.com/user/rights', 'Edit Rights');
}

function tennisthor_edit_password()
{
	tnthor_display_iframe('https://www.tennisthor.com/changepassword', 'Edit Password');
}

function tennisthor_delete_password()
{
	tnthor_display_iframe('https://www.tennisthor.com/user/deleteprofile', 'Delete Profile');
}



?>