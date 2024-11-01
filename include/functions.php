<?php
defined('TENNISTHOR_ENCRYPTION_KEY') OR define('TENNISTHOR_ENCRYPTION_KEY', 'TENNIS-RANDOM-KEY'); 

if(!function_exists('tnthor_redirect'))
{
	function tnthor_redirect($uri) {
		echo '<script>window.location.href="'.site_url().''.$uri.'"</script>';
		exit;
	}	
}


if(!function_exists('tennisthor_display_messages'))
{
	function tennisthor_display_messages()
	{
		if(isset($_SESSION['front_success_message']))
		{
			$msg = $_SESSION['front_success_message'];
			unset($_SESSION['front_success_message']);
		    return '<div class="notice notice-success is-dismissible">
		        <p>'.$msg.'</p>
		    </div>';			
		}

		if(isset($_SESSION['front_error_messages']))
		{ 
			$html = '<div class="notice notice-error is-dismissible">';
			foreach($_SESSION['front_error_messages'] as $errs)
			{
				$html .= '<p>'.$errs.'</p>';
			}
			$html .= '</div>';
			unset($_SESSION['front_error_messages']);	
			return $html;
	    }
	}
}

if(!function_exists('set_user_session'))
{
	function set_user_session($user)
	{
		$user_array = array(
			'uid'=>$user[0]['user_id'],
			'name_f'=>$user[0]['name_f'],
			'name_l'=>$user[0]['name_l'],
			'email'=>$user[0]['email'],
		);
		$_SESSION['tennithor_user_'] = $user_array;
	}
}

if(!function_exists('tennisthor_encrypt_decrypt'))
{
function tennisthor_encrypt_decrypt($action, $string) 
{
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = 'This is my secret key '.TENNISTHOR_ENCRYPTION_KEY;
    $secret_iv = 'This is my secret iv '.TENNISTHOR_ENCRYPTION_KEY;

    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}
}

if(!function_exists('tennisthor_toBase'))
{
function tennisthor_toBase($num, $b=62) 
{		
	$base='gY4fEKd0TMDqxhW1vBR2Pj8zSAJbwy6oN7aiCrUGHtIu9kFpnQlX3O5msLcZeV';
	$r = $num  % $b ;
	$res = $base[$r];
	$q = floor($num/$b);
	while ($q) {
		$r = $q % $b;
		$q =floor($q/$b);
		$res = $base[$r].$res;
	}
	return $res;
}
}

if(!function_exists('recursive_sanitize_text_field'))
{
function recursive_sanitize_text_field($array) {
    foreach ( $array as $key => &$value ) {
        if (!is_array( $value ) ) 
        {
        	if($key == "email")
        	{
        		$value = sanitize_email($value);
        	}
        	else
        	{
        		$value = sanitize_text_field( $value );	
        	}
        }
    }

    return $array;
}
}
?>