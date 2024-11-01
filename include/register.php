<?php

add_action('admin_post_nopriv_tennisthor_email_verify_hook','tennisthor_email_verify_hook_callback');
add_action('admin_post_tennisthor_email_verify_hook','tennisthor_email_verify_hook_callback');

if(!function_exists('tennisthor_email_verify_hook_callback'))
{
function tennisthor_email_verify_hook_callback()
{
	global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
	if(trim($tennis_club_id) == "")
		return false;

	$current_lang = '';
	if(isset($_COOKIE['tennis_lang']))
		$current_lang = $_COOKIE['tennis_lang'];	
		
	$body = [];				
	$body['tennisthor_verify_token'] = sanitize_text_field($_GET['verify_token']);
	$response = wp_remote_post( 'https://www.tennisthor.com/api/apiauth/activation', array(
	    'body'    => $body,
	    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
	) );	

	if($response['response']['code'] == 200)
	{			
		$_SESSION['front_success_message'] = "Your account has been verified, You can login now.";

		$page_option = get_option('tennisthor_login_page');
		$page = get_page($page_option);
		$login_page_link = get_page_link($page);			
		wp_redirect($login_page_link);			
	}	
	else
	{
		$body = json_decode($response['body'], true);
		$_SESSION['front_error_messages'] = array($body['message']);		

		$page_option = get_option('tennisthor_login_page');
		$page = get_page($page_option);
		$login_page_link = get_page_link($page);			
		wp_redirect($login_page_link);	
	}
}
}

add_action('admin_post_nopriv_tennisthor_email_valid_hook','tennisthor_email_valid_hook_callback');
add_action('admin_post_tennisthor_email_valid_hook','tennisthor_email_valid_hook_callback');
if(!function_exists('tennisthor_email_valid_hook_callback'))
{
function tennisthor_email_valid_hook_callback()
{
	global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
	if(trim($tennis_club_id) == "")
		return false;

	$current_lang = '';
	if(isset($_COOKIE['tennis_lang']))
		$current_lang = $_COOKIE['tennis_lang'];	
	
	$body = recursive_sanitize_text_field($_POST);				
	$response = wp_remote_post( 'https://www.tennisthor.com/signup/email-duplication', array(
	    'body'    => $body,
	    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
	) );	

	if($response['response']['code'] == 200)
	{			
		echo $response['body'];		
	}
}
}


add_action('admin_post_nopriv_tennisthor_search_city_hook','tennisthor_search_city_hook_callback');
add_action('admin_post_tennisthor_search_city_hook','tennisthor_search_city_hook_callback');
if(!function_exists('tennisthor_search_city_hook_callback'))
{
function tennisthor_search_city_hook_callback()
{
	global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
	if(trim($tennis_club_id) == "")
		return false;

	$current_lang = '';
	if(isset($_COOKIE['tennis_lang']))
		$current_lang = $_COOKIE['tennis_lang'];

	$body = recursive_sanitize_text_field($_POST);		
	$response = wp_remote_post( 'https://www.tennisthor.com/city/search_city/'.sanitize_text_field($_POST['cityText']), array(
	    'body'    => $body,
	    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
	) );	

	if($response['response']['code'] == 200)
	{			
		echo $response['body'];		
	}
}
}

add_action('admin_post_nopriv_tennisthor_register_hook','the_register_hook_callback');
add_action('admin_post_tennisthor_register_hook','the_register_hook_callback');


if(!function_exists('the_register_hook_callback'))
{
function the_register_hook_callback()
{	
	global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
	if(trim($tennis_club_id) == "")
		return false;

	$current_lang = '';
	if(isset($_COOKIE['tennis_lang']))
		$current_lang = $_COOKIE['tennis_lang'];
	
	if(isset($_POST['action']) AND sanitize_text_field($_POST['action']) == 'tennisthor_register_hook')
	{
		$body = recursive_sanitize_text_field($_POST);
		$body['wordpress_verify_link'] = TENNISTHOR_ADMIN_URL.'admin-post.php?action=tennisthor_email_verify_hook';
		$response = wp_remote_post( 'https://www.tennisthor.com/api/apiauth/regauth', array(
		    'body'    => $body,
		    'timeout' => 200,
		    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
		) );


		if($response['response']['code'] == 200)
		{			
			$body = json_decode($response['body'], true);		

			$page_option = get_option('tennisthor_login_page');
			$page = get_page($page_option);
			$login_page_link = get_page_link($page);
			$_SESSION['front_success_message'] = "Registration has been successfully completed. please verify your email.";

			wp_redirect($login_page_link);
			exit;
		}
		else
		{
			$body = json_decode($response['body'], true);
			$_SESSION['front_error_messages'] = array($body['message']);
		}

		$page_option = get_option('tennisthor_register_page');
		$page = get_page($page_option);
		$register_page_link = get_page_link($page);		

		wp_redirect($register_page_link);
	}	
}
}

if (!is_admin()) 
{
	add_shortcode( 'tennisthor_register', 'tennisthor_register_shortcode' );
	if(!function_exists('tennisthor_register_shortcode'))
	{	
	function tennisthor_register_shortcode() 
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

		$body = array();
		$response = wp_remote_post('https://www.tennisthor.com/api/apiauth/index', array(
		    'body'    => $body,
		    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
		) );

		if($response['response']['code'] == 200)
		{			
			$body = json_decode($response['body'], true);		
		}

		//wp_enqueue_script( 'jquery-ui-autocomplete', '', array( 'jquery-ui-widget', 'jquery-ui-position' ), '1.8.6' ); //don't loads the autocomplete
		wp_enqueue_script( 'jquery-ui-autocomplete'); //same as abov		
		wp_enqueue_script('tennisthor-signup-0', plugins_url('../assets/js/jquery.validate.min.js',__FILE__));
		wp_enqueue_script('tennisthor-signup-1', plugins_url('../assets/js/additional-methods.min.js',__FILE__));
		wp_enqueue_script('tennisthor-signup-2', plugins_url('../assets/js/bootstrap-datepicker.js',__FILE__));		
		wp_enqueue_style('tennisthor-signup-0', plugins_url('../assets/css/flags32.css',__FILE__));
		wp_enqueue_style('tennisthor-signup-1', plugins_url('../assets/css/datepicker3.css',__FILE__));
		wp_enqueue_script('tennisthor-signup-3', plugins_url('../assets/js/signup.js',__FILE__));
		
		wp_enqueue_style('tennis-thor-signup-1', plugins_url('../assets/css/jQueryUI/jquery-ui.css',__FILE__));
		?>

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

		<div class="passwordBox animated fadeInDown">
			<?php echo tennisthor_display_messages();?>

		<h3><?php echo $body['header_txt'];?></h3>

		<form class="m-t form-horizontal clubsitefrmcls" role="form" name="signupfrm" id="signupfrm" method="post" action="<?php echo TENNISTHOR_ADMIN_URL;?>admin-post.php">
			<input type="hidden" name="action" value="tennisthor_register_hook" />
			<input type="hidden" name="tm_zone" id="tm_zone" value=""  />

			<div class="form-group">            		
		        <input type="email" maxlength="120" class="form-control" name="email" id="email" placeholder="<?php echo $body['email_txt'];?>" >
		    </div>
		    <div class="form-group">
		        <input type="text" maxlength="50" class="form-control" name="name_f" id="name_f" placeholder="<?php echo $body['firstname_txt'];?>" >
		    </div>
		    <div class="form-group">
		        <input type="text" maxlength="50" class="form-control" name="name_l" id="name_l" placeholder="<?php echo $body['lastname_txt'];?>" >
		    </div>
		    
		    <div class="form-group">
		        <input type="text" maxlength="128" class="form-control" name="nickname" id="nickname" placeholder="<?php echo $body['nickname_txt'];?>" >
		    </div>

		    <div class="form-group">
		    	<div class="text-left">
		    	<?php foreach($body['sexes'] as $sex):
		    	$checked = "";
		    	if(isset($body['sex_id']) AND $body['sex_id'] == $sex['code'])
		    		$checked = "checked=''";
		    	?>
		        	<div class="i-checks"><label> <input type="radio" value="<?php echo $sex['list_user_id'];?>" name="sex" <?php echo $checked;?>> <i></i> <?php echo esc_attr($sex['string']);?> </label></div>
		        <?php endforeach;?>
		        </div>
		    </div>	

		    <div class="form-group orderdate">
		        <div class="input-group date">
		        	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="birthday" id="birthday" placeholder="<?php echo $body['birthday_txt'];?>" class="form-control" value="" />
		        </div>                    
		    </div>

			<div class="form-group">
				<select class="select2_demo_3 form-control" name="geoname_country_id" id="geoname_country_id">
		            <option value=""><?php echo $body['choose_country_txt'];?></option> 
		            <?php foreach($body['country'] as $c_row):
		            
		            	$selected = '';
		            	if(isset($body['country_geonameid']) AND $body['country_geonameid'] == $c_row['code'])
		            		$selected = 'selected=""';
		            ?>
		            	<option value="<?php echo $c_row['code']; ?>" <?php echo $selected;?>><?php echo esc_attr($c_row['name']); ?></option> 	
		            <?php endforeach;?>
		        </select>
			</div>	

		    <div class="form-group">
		    	<label>City</label>
		    	<input maxlength="80" autocomplete="off" type="hidden" placeholder="<?php echo $body['city_txt'];?>" name="city_id" id="city_id" class="form-control" value="<?php echo (isset($body['city_id']) ? esc_attr($body['city_id']) : '' );?>">    	
		    	<input class="form-control cityText typeahead_2" placeholder=" &nbsp; " type="text" value="<?php echo (isset($body['city_id']) ? $body['city_id'] : '' );?>" />
		    	<input type="hidden" name="geoname_id" id="geoname_id"  value="<?php echo (isset($body['geoname_id']) ? $body['geoname_id'] : '' );?>">
		    	<div class="city_no_suggestions" style="display: none;"><?php echo $body['no_suggestion_txt'];?></div>
		        <span class="required_star"><?php echo $body['city_note_txt'];?></span>
		    </div>	


		    <div class="form-group hidden">
		        <input maxlength="80" type="text" readonly="" placeholder="<?php echo $body['country_txt'];?>" name="country_name" id="country_name" class="form-control" value="<?php echo (isset($body['country_name']) ? esc_attr($body['country_name']) : '' );?>">
		    </div> 
		    
		    <div class="form-group">
		        <input type="text" maxlength="15" class="form-control" name="phone" id="phone" placeholder="<?php echo $body['phone_no_txt'];?>" >
		    </div>
		    
		    <div class="form-group">
		        <input type="password" maxlength="30" class="form-control" name="password" id="password" placeholder="<?php echo $body['password_txt'];?>" >
		    </div>
		    <div class="form-group">
		        <input type="password" maxlength="30" class="form-control" name="confirm_password" id="confirm_password" placeholder="<?php echo $body['confirm_password_txt'];?>" >
		    </div>	

		    <div class="form-group">                	
		    	<div class="text-left">
		    		<?php foreach($body['sports'] as $key=>$sport):
		    			if($key == "0")
		    				continue;
		    			
		    			$checked = '';
		    			if(isset($body['usports']))
		    			{
							if(in_array($key,$body['usports']))
							{
								$checked = 'checked=""';		
							}
						}
						
						if($body['sports_checked'][$key] != "")
							$checked = 'checked=""';				
		    		?>
		    			<div class="i-checks"><label> <input type="checkbox" name="usports[]" value="<?php echo $key;?>" <?php echo $checked;?>> <i></i> <?php echo esc_attr($sport);?> </label></div>
		        	<?php endforeach;?>
		        </div>
		    </div>		    	    	

		    <div class="clearfix m-t"></div>                
		                    
		    <div class="form-group">
		    	<div class="text-left">
		            <div class="i-checks"><label> <input type="checkbox" name="termscond" id="termscond" value="1"><i></i> <span class="required_star">*</span> <a href="<?php echo TENNISTHOR_DOMAIN;?>/<?php echo $body['lang_code'];?>/terms_and_conditions" target="_blank" class="navy-link"><?php echo $body['tnc_txt'];?></a> </label></div>
		        </div>
		    </div>
		    
		    <div class="form-group">
		    	<div class="text-left">
		            <div class="i-checks"><label> <input type="checkbox" name="privacy" id="privacy" value="1"><i></i> <span class="required_star">*</span> <a href="<?php echo TENNISTHOR_DOMAIN;?>/<?php echo $body['lang_code'];?>/privacy" target="_blank" class="navy-link"><?php echo $body['privacy_txt'];?></a> </label></div>
		        </div>
		    </div>
		    
		    <div class="form-group">
		    	<div class="text-left">
		            <div class="i-checks"><label> <input type="checkbox" name="cookies" id="cookies" value="1"><i></i> <span class="required_star">*</span> <a href="<?php echo TENNISTHOR_DOMAIN;?>/<?php echo $body['lang_code'];?>/cookies" target="_blank" class="navy-link"><?php echo $body['cookie_txt'];?></a> </label></div>
		        </div>
		    </div>
		    
		    <div class="form-group">
		    	<label><?php echo $body['event_note_txt'];?></label>
		    	<div class="text-left">
		            <div class="i-checks"><label> <input type="checkbox" name="tournament_email" id="tournament_email" value="tournament_email"><i></i> <a href="#" class="navy-link"><?php echo $body['by_email_txt'];?></a> </label></div>
		            <div class="i-checks"><label> <input type="checkbox" name="tournament_phone" id="tournament_phone" value="tournament_phone"><i></i> <a href="#" class="navy-link"><?php echo $body['by_phone_txt'];?></a> </label></div>
		            <div class="i-checks"><label> <input type="checkbox" name="tournament_sms" id="tournament_sms" value="tournament_sms"><i></i> <a href="#" class="navy-link"><?php echo $body['by_sms_txt'];?></a> </label></div>
		            <div class="i-checks"><label> <input type="checkbox" name="tournament_viber" id="tournament_viber" value="tournament_viber"><i></i> <a href="#" class="navy-link"><?php echo $body['by_viber_txt'];?></a> </label></div>
		        </div>
		    </div>
		    
		    <div class="form-group">
		    	<label><?php echo $body['promo_txt'];?></label>
		    	<div class="text-left">
		            <div class="i-checks"><label> <input type="checkbox" name="news" id="news" value="news"><i></i> <a href="#" class="navy-link"><?php echo $body['i_agree_txt'];?></a> </label></div>
		        </div>
		    </div>
		    
		    <div class="form-group">
		    	<label> <?php echo $body['new_promo_txt'];?></label>
		    	<div class="text-left">
		            <div class="i-checks"><label> <input type="checkbox" name="promotion_shops" id="promotion_shops" value="promotion_shops"><i></i> <a href="#" class="navy-link"><?php echo $body['i_agree_txt'];?></a> </label></div>
		        </div>
		    </div>
		    
		    <button type="submit" class="btn btn-primary block full-width m-b" onclick="jQuery('#signupfrm').valid();"><?php echo $body['reg_txt'];?></button>			    		
<?php
		$page_option = get_option('tennisthor_login_page');
		$page = get_page($page_option);
		$login_page_link = get_page_link($page);	
?>
			<p class="text-muted text-center"><small><?php echo $body['have_acc_txt'];?></small></p>
		    <a class="btn btn-sm btn-white btn-block button_color" href="<?php echo $login_page_link;?>"><?php echo $body['login_txt'];?></a>		    	    	    
		</form>

		</div>

		<script type="text/javascript">
			var tennisthor_wpadmin_url = '<?php echo TENNISTHOR_ADMIN_URL;?>';
			var js_validation_txt = '<?php echo $body['js_validation_txt'];?>';
			var js_msg1_txt = '<?php echo $body['js_msg1_txt'];?>';
			var js_msg2_txt = '<?php echo $body['js_msg2_txt'];?>';
			var js_msg3_txt = '<?php echo $body['js_msg3_txt'];?>';
			var js_msg4_txt = '<?php echo $body['js_msg4_txt'];?>';
			var js_msg5_txt = '<?php echo $body['js_msg5_txt'];?>';
			var js_msg6_txt = '<?php echo $body['js_msg6_txt'];?>';
			var js_msg7_txt = '<?php echo $body['js_msg7_txt'];?>';
			var js_msg8_txt = '<?php echo $body['js_msg8_txt'];?>';
			
		</script>
		<?php

		
	}
	}

}
?>