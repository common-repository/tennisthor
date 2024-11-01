<?php
// Register a new shortcode: 


add_action('admin_post_nopriv_tennisthor_logout_hook','the_logout_hook_callback');
add_action('admin_post_tennisthor_logout_hook','the_logout_hook_callback');

if(!function_exists('tennisthor_auto_login_rememberme')) 
{
	function tennisthor_auto_login_rememberme()
	{
		if(!isset($_SESSION['tennithor_user_']) AND isset($_COOKIE['remember_me_uid']))
		{
			$auto_login_user_id = $_COOKIE['remember_me_uid'];
			$response = wp_remote_post( 'https://www.tennisthor.com/api/user/remembermelogin', array(
			    'body'    => array('tennisthor_remember_me_uid' => $auto_login_user_id),
			    'headers' => array(),
			) );
			
			if($response['response']['code'] == 200)
			{
				$body = json_decode($response['body'], true);
				set_user_session($body['user']);
				tnthor_redirect('/');
			}	
		}	
	}	
}


if(!function_exists('the_logout_hook_callback')) 
{
	function the_logout_hook_callback()
	{
		if(isset($_POST['action']) AND sanitize_text_field($_POST['action']) == 'tennisthor_logout_hook')
		{
			if(isset($_SESSION['tennithor_user_']))
			{
				if(isset($_COOKIE['remember_me_uid']))
				{
					unset($_COOKIE['remember_me_uid']);								
					$name = 'remember_me_uid';
					$value = "";
					$expire = time() - (15*60);
					setcookie($name, $value, $expire, '/');							
				}
				unset($_SESSION['tennithor_user_']);
			}
			
			wp_redirect($_POST['_wp_http_referer']);		
		}
	}
}

add_action('admin_post_nopriv_tennisthor_login_hook','tennisthor_login_hook_callback');
add_action('admin_post_tennisthor_login_hook','tennisthor_login_hook_callback');


if(!function_exists('tennisthor_login_hook_callback')) 
{
	function tennisthor_login_hook_callback()
	{	
		if(isset($_POST['action']) AND sanitize_text_field($_POST['action']) == 'tennisthor_login_hook')
		{
			$response = wp_remote_post( 'https://www.tennisthor.com/api/user/frontlogin', array(
			    'body'    => array('tennisthor_email' => sanitize_text_field($_POST['email']), 'tennisthor_password' => sanitize_text_field($_POST['password']), 'tennisthor_tmzone' => sanitize_text_field($_POST['tennisthor_tmzone'])),
			    'headers' => array(),
			) );

			if($response['response']['code'] == 200)
			{			
				$body = json_decode($response['body'], true);		
				wp_redirect(TENNISTHOR_DOMAIN.'/?wpautoses='.$body['enc_player_user_id']);
				exit;
					
				set_user_session($body['user']);
				
				if(isset($_POST['remember_me']))
				{
					$name = 'remember_me_uid';
					$value = tennisthor_encrypt_decrypt('encrypt',$body['user'][0]['user_id']);
					$expire = time()+86400*365;
					setcookie($name, $value, $expire,'/');					
				}
				else
				{
					unset($_COOKIE['remember_me_uid']);								
					$name = 'remember_me_uid';
					$value = "";
					$expire = time() - (15*60);
					setcookie($name, $value, $expire);
				}			
			}
			else
			{
				$body = json_decode($response['body'], true);
				$_SESSION['front_error_messages'] = array($body['message']);
			}

			wp_redirect(sanitize_text_field($_POST['_wp_http_referer']));
		}	
	}
}
 
// The callback function that will replace
if (!is_admin()) 
{
	add_shortcode( 'tennisthor_login', 'tennisthor_login_shortcode' );

	if(!function_exists('tennisthor_login_shortcode')) 
	{	
		function tennisthor_login_shortcode() 
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
			$response = wp_remote_post('https://www.tennisthor.com/api/apiauth/logindetail', array(
			    'body'    => $body,
			    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
			) );

			if($response['response']['code'] == 200)
			{			
				$body = json_decode($response['body'], true);		
			}					
			
			tennisthor_auto_login_rememberme();
			
			$yui_js = plugins_url('../assets/js/login.js',__FILE__);
			wp_enqueue_script('tennisthor', $yui_js);

			if(isset($_SESSION['tennithor_user_']))
			{
				?>
				Welcome <?php echo $_SESSION['tennithor_user_']['name_f'];?>,
				
				<form class="m-t" role="form" method="post" name="loginform" id="loginform" action="<?php echo TENNISTHOR_ADMIN_URL;?>admin-post.php">
					<input type="hidden" name="action" value="tennisthor_logout_hook" />
					<?php wp_nonce_field();?>
					<button type="submit" name="tennisthor_logout" class="btn btn-primary block full-width m-b">Logout</button>
				</form>		
				<?php
			}
			else
			{	
				echo tennisthor_display_messages();

				$rem_check = '';
				if(isset($remember_me)){ $rem_check = 'checked'; }

				$html = '';
			    $html .= '<h3>'.$body['login_txt1'].'</h3>
				<form class="loginscreen tennisthor_login_cls m-t" role="form" method="post" name="loginform" id="loginform" action="'.TENNISTHOR_ADMIN_URL.'admin-post.php">
					<input type="hidden" name="action" value="tennisthor_login_hook" />
					<input type="hidden" name="tennisthor_tmzone" id="tennisthor_tmzone" value=""  />';
					$html .= wp_nonce_field();
					
					$html .= '<div class="form-group">
					    <input type="email" maxlength="120" class="form-control" name="email" id="email" placeholder="'.$body['login_txt2'].'" value="" required="">
					</div>                
					<div class="form-group">
					    <input type="password" maxlength="30" class="form-control" name="password" id="password" placeholder="'.$body['login_txt3'].'" value="" required="">
					</div>
					
		            <div class="form-group">
		                <div class="checkbox i-checks"><label> <input type="checkbox" name="remember_me" id="remember_me" value="1" '.$rem_check.'><i></i> '.$body['login_txt4'].' </label></div>
		            </div>'; 			
		            
		            $html .= '<button type="submit" name="tennisthor_login" class="btn btn-primary block full-width m-b">'.$body['login_txt5'].'</button>';

			$page_option = get_option('tennisthor_register_page');
			$page = get_page($page_option);
			$register_page_link = get_page_link($page);		            

	                $html .= '<p class="text-muted text-center"><small>'.$body['login_txt7'].'</small></p>
	                <a class="btn btn-sm btn-white btn-block button_color" href="'.$register_page_link.'">'.$body['login_txt6'].'</a>			
				</form>';



				return $html;
		    }
		}
	}

}
?>